<?php
// public/api/admin/gallery-upload-handler.php

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json');

// Ensure errors are logged but not displayed to avoid breaking JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// 1. Auth & CSRF Check
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// 2. CSRF Check
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    error_log("Gallery Upload: Invalid CSRF token from USER ID: " . ($_SESSION['user_id'] ?? 'unknown'));
    exit;
}

// 3. Prepare DB & Response
$pdo = getDBConnection();
$response = ['success' => false, 'messages' => [], 'files' => [], 'warning' => null];

// Check if image processing is available (for warning)
if (!extension_loaded('gd') && !extension_loaded('imagick')) {
    $response['warning'] = 'Notice: Image processing library (GD or Imagick) is missing. Images will be uploaded as-is without resizing or thumbnails.';
}

try {
    // 2. Handle Event Selection/Creation
    $eventId = $_POST['event_id'] ?? null;
    $newEventTitle = trim($_POST['new_event_title'] ?? '');

    if (!empty($newEventTitle)) {
        // Create new event
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $newEventTitle)));
        // Ensure unique slug
        $stmt = $pdo->prepare("SELECT count(*) FROM gallery_events WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . time();
        }

        $stmt = $pdo->prepare("INSERT INTO gallery_events (title, slug, created_by) VALUES (?, ?, ?)");
        $stmt->execute([$newEventTitle, $slug, $_SESSION['user_id']]);
        $eventId = $pdo->lastInsertId();
    }

    if (empty($eventId)) {
        throw new Exception("No event selected or created.");
    }

    // 3. Prepare Upload Directory
    $uploadDir = __DIR__ . '/../../../public/uploads/gallery/' . $eventId . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 4. Process Images
    $files = $_FILES['images'] ?? [];
    $hasFiles = !empty($files['name'][0]);

    if (!$hasFiles) {
        // If no files, but we created an event, return success
        if (!empty($newEventTitle)) {
            echo json_encode([
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $eventId,
                'processed_count' => 0
            ]);
            exit;
        } else {
            throw new Exception("No files uploaded.");
        }
    }

    $fileCount = count($files['name']);
    $successCount = 0;

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $errorMsg = match ($files['error'][$i]) {
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                default => 'Unknown upload error'
            };
            $response['messages'][] = "Error uploading " . $files['name'][$i] . ": " . $errorMsg;
            continue;
        }

        // Validate Type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($files['tmp_name'][$i]);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowedMimes)) {
            $response['messages'][] = "Invalid file type: " . $files['name'][$i];
            continue;
        }

        // Validate Size (8MB)
        if ($files['size'][$i] > 8 * 1024 * 1024) {
            $response['messages'][] = "File too large: " . $files['name'][$i];
            continue;
        }

        // Generate Filename
        $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $randomName = bin2hex(random_bytes(16));
        $filename = $randomName . '.' . $ext;
        $targetPath = $uploadDir . $filename;
        $thumbPath = $uploadDir . 'thumb_' . $filename;

        // Process Image (Resize/Crop)
        if (processImage($files['tmp_name'][$i], $targetPath, $thumbPath, $mime)) {
            // Get Dimensions of final full image
            list($width, $height) = getimagesize($targetPath);

            // Insert DB
            $stmt = $pdo->prepare("INSERT INTO gallery_images (event_id, filename, orig_name, mime_type, width, height, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $eventId,
                $filename, // Currently storing just filename. Path is implied /uploads/gallery/{event_id}/
                $files['name'][$i],
                $mime,
                $width,
                $height
            ]);

            $successCount++;
            $response['files'][] = [
                'id' => $pdo->lastInsertId(),
                'filename' => $filename
            ];
        } else {
            $response['messages'][] = "Failed to process image: " . $files['name'][$i];
        }
    }

    $response['success'] = true;
    $response['processed_count'] = $successCount;
    $response['event_id'] = $eventId; // Return for redirect

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);


/**
 * Helper to process image
 */
function processImage($source, $dest, $destThumb, $mime)
{
    try {
        // Preferred: Imagick
        if (extension_loaded('imagick')) {
            $image = new Imagick($source);

            // Auto orient based on EXIF
            try {
                $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
            } catch (Exception $e) { /* ignore */
            }

            // 1. Save Full Version (Max width 1600)
            $full = clone $image;
            if ($full->getImageWidth() > 1600) {
                $full->scaleImage(1600, 0);
            }
            $full->writeImage($dest);
            $full->clear();

            // 2. Save Thumbnail (Crop 480x270 - 16:9)
            $thumb = clone $image;
            $thumb->cropThumbnailImage(480, 270);
            $thumb->writeImage($destThumb);
            $thumb->clear();

            $image->clear();
            return true;
        }

        // Fallback: GD
        if (extension_loaded('gd')) {
            list($srcW, $srcH) = getimagesize($source);
            $imgFn = match ($mime) {
                'image/jpeg' => 'imagecreatefromjpeg',
                'image/png' => 'imagecreatefrompng',
                'image/webp' => 'imagecreatefromwebp',
                default => null
            };

            if (!$imgFn)
                return false;
            $srcImg = $imgFn($source);

            if (!$srcImg)
                return false;

            // 1. Full Version
            $newW = $srcW;
            $newH = $srcH;
            if ($srcW > 1600) {
                $newW = 1600;
                $newH = ($srcH / $srcW) * 1600;
            }

            $fullImg = imagecreatetruecolor($newW, $newH);
            // Preserve transparency for PNG/WebP
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($fullImg, false);
                imagesavealpha($fullImg, true);
            }
            imagecopyresampled($fullImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

            $saveFn = match ($mime) {
                'image/jpeg' => fn($img, $path) => imagejpeg($img, $path, 85),
                'image/png' => fn($img, $path) => imagepng($img, $path, 8),
                'image/webp' => fn($img, $path) => imagewebp($img, $path, 85),
            };
            $saveFn($fullImg, $dest);
            imagedestroy($fullImg);

            // 2. Thumbnail (Crop 480x270)
            $thumbInd = imagecreatetruecolor(480, 270);
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($thumbInd, false);
                imagesavealpha($thumbInd, true);
            }

            // Calculate crop
            $aspectRatio = 480 / 270;
            $srcRatio = $srcW / $srcH;

            if ($srcRatio > $aspectRatio) {
                // Source is wider, crop width
                $tempH = $srcH;
                $tempW = $srcH * $aspectRatio;
                $srcX = ($srcW - $tempW) / 2;
                $srcY = 0;
            } else {
                // Source is taller, crop height
                $tempW = $srcW;
                $tempH = $srcW / $aspectRatio;
                $srcX = 0;
                $srcY = ($srcH - $tempH) / 2;
            }

            imagecopyresampled($thumbInd, $srcImg, 0, 0, $srcX, $srcY, 480, 270, $tempW, $tempH);
            $saveFn($thumbInd, $destThumb);

            imagedestroy($thumbInd);
            imagedestroy($srcImg);
            return true;
        }

        // Final Fallback: Just copy the files (no processing libraries available)
        if (copy($source, $dest)) {
            copy($source, $destThumb);
            return true;
        }

        return false;

    } catch (Exception $e) {
        error_log("Image processing error: " . $e->getMessage());
        return false;
    }
}
