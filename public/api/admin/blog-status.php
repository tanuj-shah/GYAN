<?php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../config/database.php';

if (!isAdmin()) {
    setFlashMessage('error', 'Unauthorized access.');
    redirect('../../login.php');
}

$pdo = getDBConnection();

// Handle Delete (GET request for simplicity in this case, but still with token)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (!verifyCSRFToken($_GET['token'] ?? '')) {
        setFlashMessage('error', 'CSRF token validation failed.');
        redirect('../../admin/blogs.php');
    }

    $id = (int) $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Blog deleted successfully.');
    } catch (PDOException $e) {
        setFlashMessage('error', 'Error deleting blog: ' . $e->getMessage());
    }
    redirect('../../admin/blogs.php');
}

// Handle Status Update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = (int) ($_POST['blog_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!verifyCSRFToken($csrf_token)) {
        setFlashMessage('error', 'CSRF token validation failed.');
    } elseif ($blog_id > 0 && in_array($status, ['approved', 'rejected'])) {
        try {
            $stmt = $pdo->prepare("UPDATE blogs SET status = ? WHERE id = ?");
            $stmt->execute([$status, $blog_id]);
            setFlashMessage('success', 'Blog status updated to ' . $status . '.');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Error updating blog status: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('error', 'Invalid request.');
    }

    $redirectStatus = $status === 'approved' ? 'pending' : ($status === 'rejected' ? 'pending' : 'pending'); // Stay on current view or go to pending
    redirect('../../admin/blogs.php?status=' . $status);
}

redirect('../../admin/blogs.php');
