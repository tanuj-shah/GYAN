<?php
// public/api/vision2035/get_notifications.php
// Fetch user notifications

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

$unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';

$notifications = getUserNotifications($_SESSION['user_id'], $unreadOnly);
$unreadCount = getUnreadNotificationCount($_SESSION['user_id']);

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread_count' => $unreadCount
]);
