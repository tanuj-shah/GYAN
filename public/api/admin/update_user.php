<?php
// api/admin/update_user.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/admin.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../admin/users.php');
    }

    // Input validation
    $userId = filter_var($_POST['user_id'] ?? 0, FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';

    // Validate user ID
    if ($userId === false || $userId < 1) {
        setFlashMessage('error', 'Invalid user ID');
        redirect('../../admin/users.php');
    }

    // Whitelist validation for status
    $allowedStatuses = ['pending', 'active', 'suspended'];
    if (!in_array($status, $allowedStatuses)) {
        setFlashMessage('error', 'Invalid status value');
        redirect('../../admin/users.php');
    }

    // Prevent admins from modifying their own status
    if ($userId == $_SESSION['user_id']) {
        setFlashMessage('error', 'You cannot modify your own account status');
        redirect('../../admin/users.php');
    }

    // Verify user exists and is not another admin (extra protection)
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $targetUser = $stmt->fetch();

    if (!$targetUser) {
        setFlashMessage('error', 'User not found');
        redirect('../../admin/users.php');
    }

    if ($targetUser['role'] === 'admin') {
        setFlashMessage('error', 'Cannot modify admin user status');
        redirect('../../admin/users.php');
    }

    // Update status
    if (updateUserStatus($userId, $status)) {
        setFlashMessage('success', 'User status updated to ' . $status);
    } else {
        setFlashMessage('error', 'Failed to update status');
    }

    redirect('../../admin/users.php');
}
