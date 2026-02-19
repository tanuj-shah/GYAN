<?php
// public/api/admin/delete_gallery_image.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/admin.php';

header('Content-Type: application/json');

// 1. Check Admin Auth
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// 2. Verify CSRF
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$imageId = $_POST['image_id'] ?? null;

if (!$imageId) {
    echo json_encode(['success' => false, 'message' => 'Missing Image ID']);
    exit;
}

try {
    $pdo = getDBConnection();

    // Fetch image details to get the file path
    $stmt = $pdo->prepare("SELECT filename, event_id FROM gallery_images WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch();

    if (!$image) {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
        exit;
    }

    // Start Transaction
    $pdo->beginTransaction();

    // Delete from Database
    $deleteStmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
    $deleteStmt->execute([$imageId]);

    // Delete from Filesystem
    $filePath = __DIR__ . '/../../../public/uploads/gallery/' . $image['event_id'] . '/' . $image['filename'];
    $thumbPath = __DIR__ . '/../../../public/uploads/gallery/' . $image['event_id'] . '/thumb_' . $image['filename'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }
    if (file_exists($thumbPath)) {
        unlink($thumbPath);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);

} catch (Exception $e) {
    if (isset($pdo))
        $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}
