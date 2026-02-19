<?php
// public/api/admin/delete_gallery_event.php
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

$eventId = $_POST['event_id'] ?? null;

if (!$eventId) {
    echo json_encode(['success' => false, 'message' => 'Missing Event ID']);
    exit;
}

try {
    $pdo = getDBConnection();

    // Start Transaction
    $pdo->beginTransaction();

    // 1. Delete images from Database
    $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE event_id = ?");
    $stmt->execute([$eventId]);

    // 2. Delete event from Database
    $stmt = $pdo->prepare("DELETE FROM gallery_events WHERE id = ?");
    $stmt->execute([$eventId]);

    // 3. Delete from Filesystem
    $dirPath = __DIR__ . '/../../../public/uploads/gallery/' . $eventId;

    if (file_exists($dirPath)) {
        // Recursive deletion helper
        deleteDirectory($dirPath);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Event and all images deleted successfully']);

} catch (Exception $e) {
    if (isset($pdo))
        $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}

/**
 * Helper to delete a directory and its contents
 */
function deleteDirectory($dir)
{
    if (!file_exists($dir))
        return true;
    if (!is_dir($dir))
        return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..')
            continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
            return false;
    }
    return rmdir($dir);
}
