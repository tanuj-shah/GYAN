<?php
// api/admin/delete_user.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/admin.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../admin/users.php');
    }

    $userId = $_POST['user_id'];

    if (deleteUser($userId)) {
        setFlashMessage('success', 'User permanently removed from the alliance');
    } else {
        setFlashMessage('error', 'Failed to remove user (Note: Admins cannot be deleted via this flow)');
    }

    redirect('../../admin/users.php');
}
