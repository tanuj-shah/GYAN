<?php
// public/api/auth/verify_reset_otp.php
// Endpoint to verify reset OTP and allow password change

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        $email = $_POST['email'] ?? '';
        redirect('../../verify-reset.php?email=' . urlencode($email));
    }

    $email = sanitize($_POST['email'] ?? '');
    $otp = sanitize($_POST['otp'] ?? '');

    // Validation
    if (empty($email) || empty($otp)) {
        setFlashMessage('error', 'Email and OTP are required');
        redirect('../../verify-reset.php?email=' . urlencode($email));
    }

    // Verify OTP
    // We reuse verifyOTP logic but need to be careful NOT to activate user status if it was suspended, 
    // though for 'forgot password' we usually assume active users. 
    // However, verifyOTP function currently activates pending users. 
    // Let's make a specific check here.

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, otp_code, otp_expiry FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || $user['otp_code'] !== $otp || strtotime($user['otp_expiry']) < time()) {
        setFlashMessage('error', 'Invalid or expired verification code');
        redirect('../../verify-reset.php?email=' . urlencode($email));
    }

    // OTP Verified! 
    // Instead of logging in, we redirect to reset-password.php with a temporary token or sign the request.
    // For simplicity, we'll use a session flag.
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_verified'] = true;

    redirect('../../reset-password.php');
}
