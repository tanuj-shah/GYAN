<?php
// includes/upload.php

function handleProfileImageUpload($file)
{
    // 1. Check upload errors
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Upload failed.'];
    }

    // 2. Validate file size FIRST (max 2MB) - prevent DoS
    if ($file["size"] > 2 * 1024 * 1024) {
        return ['status' => false, 'message' => 'File too large (max 2MB).'];
    }

    // 3. Validate MIME type (check actual file content, NOT extension)
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimes)) {
        return ['status' => false, 'message' => 'Invalid file type. Only images allowed.'];
    }

    // 4. Validate that it's actually an image (prevents disguised files)
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['status' => false, 'message' => 'Not a valid image file.'];
    }

    // 5. Determine safe extension based on MIME type
    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg'
    };

    // 6. Create directory if needed
    $targetDir = __DIR__ . "/../public/uploads/profiles/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // 7. Generate cryptographically secure unique filename
    $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
    $targetFilePath = $targetDir . $newFileName;

    // 8. Move uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return ['status' => true, 'path' => 'uploads/profiles/' . $newFileName];
    } else {
        return ['status' => false, 'message' => 'Error uploading file.'];
    }
}

function handleBlogImageUpload($file)
{
    // 1. Check upload errors
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Upload failed.'];
    }

    // 2. Validate file size (max 5MB for blogs)
    if ($file["size"] > 5 * 1024 * 1024) {
        return ['status' => false, 'message' => 'File too large (max 5MB).'];
    }

    // 3. Validate MIME type (check actual file content)
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimes)) {
        return ['status' => false, 'message' => 'Invalid file type. Only images allowed.'];
    }

    // 4. Validate actual image content
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['status' => false, 'message' => 'Not a valid image file.'];
    }

    // 5. Determine safe extension
    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg'
    };

    // 6. Create directory
    $targetDir = __DIR__ . "/../public/uploads/blogs/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // 7. Generate secure unique filename
    $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
    $targetFilePath = $targetDir . $newFileName;

    // 8. Move file
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return ['status' => true, 'path' => 'uploads/blogs/' . $newFileName];
    } else {
        return ['status' => false, 'message' => 'Error uploading file.'];
    }
}

function handleEventImageUpload($file)
{
    // 1. Check upload errors
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Upload failed.'];
    }

    // 2. Validate file size (max 5MB)
    if ($file["size"] > 5 * 1024 * 1024) {
        return ['status' => false, 'message' => 'File too large (max 5MB).'];
    }

    // 3. Validate MIME type
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimes)) {
        return ['status' => false, 'message' => 'Invalid file type. Only images allowed.'];
    }

    // 4. Validate actual image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['status' => false, 'message' => 'Not a valid image file.'];
    }

    // 5. Determine extension
    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'jpg'
    };

    // 6. Create directory
    $targetDir = __DIR__ . "/../public/uploads/events/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // 7. Generate secure filename
    $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
    $targetFilePath = $targetDir . $newFileName;

    // 8. Move file
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return ['status' => true, 'path' => 'uploads/events/' . $newFileName];
    } else {
        return ['status' => false, 'message' => 'Error uploading file.'];
    }
}
