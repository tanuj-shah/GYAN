<?php
// public/api/auth/reset_password.php
// Endpoint to update password after verification

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Security check
if (!isset($_SESSION['reset_verified']) || $_SESSION['reset_verified'] !== true || !isset($_SESSION['reset_email'])) {
    setFlashMessage('error', 'Unauthorized access');
    redirect('../../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../reset-password.php');
    }

    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (empty($password) || empty($confirm)) {
        setFlashMessage('error', 'All fields required');
        redirect('../../reset-password.php');
    }

    if ($password !== $confirm) {
        setFlashMessage('error', 'Passwords do not match');
        redirect('../../reset-password.php');
    }

    if (strlen($password) < 6) {
        setFlashMessage('error', 'Password must be at least 6 characters');
        redirect('../../reset-password.php');
    }

    $email = $_SESSION['reset_email'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo = getDBConnection();

    // Update password and clear OTP
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");

    if ($stmt->execute([$hash, $email])) {
        // Clear session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);

        setFlashMessage('success', 'Password reset successfully! Please login.');
        redirect('../../login.php');
    } else {
        setFlashMessage('error', 'Failed to reset password. Please try again.');
        redirect('../../reset-password.php');
    }
}
