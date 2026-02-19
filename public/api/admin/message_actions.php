<?php
// api/admin/message_actions.php
session_start();  // Initialize session for admin check
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Admin access required.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    if (!$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid Message ID.']);
        exit;
    }

    try {
        $pdo = getDBConnection();

        if ($action === 'read') {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Message marked as read.']);
            } else {
                throw new Exception('Failed to update message status.');
            }
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Message deleted successfully.']);
            } else {
                throw new Exception('Failed to delete message.');
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid action. Use "read" or "delete".']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        error_log('Database error in message_actions.php: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        http_response_code(500);
        error_log('Error in message_actions.php: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Use POST.']);
}
