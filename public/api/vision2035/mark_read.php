<?php
// public/api/vision2035/mark_read.php
// Mark notification as read

session_start();
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/vision2035.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

// Validate input
if (empty($_POST['notification_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Notification ID is required.']);
    exit;
}

$notificationId = (int) $_POST['notification_id'];

// Verify notification belongs to user
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT user_id FROM vision_notifications WHERE id = ?");
$stmt->execute([$notificationId]);
$notification = $stmt->fetch();

if (!$notification || $notification['user_id'] != $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

// Mark as read
$result = markNotificationRead($notificationId);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Notification marked as read.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update notification.']);
}
