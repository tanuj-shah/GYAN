<?php
// public/api/auth/send_reset_otp.php
// Endpoint to send password reset OTP

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../forgot-password.php');
    }

    $email = sanitize($_POST['email'] ?? '');

    if (empty($email)) {
        setFlashMessage('error', 'Email is required');
        redirect('../../forgot-password.php');
    }

    // Check if user exists
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate and Save OTP
        $otp = generateOTP();
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE id = ?");
        $stmt->execute([$otp, $expiry, $user['id']]);

        // Send Email
        $subject = "Reset Your GYAN Password";
        $body = "
            <h2>Password Reset Request</h2>
            <p>You requested to reset your password. Your verification code is:</p>
            <h1>$otp</h1>
            <p>This code will expire in 15 minutes.</p>
            <p>If you did not request this, please ignore this email.</p>
        ";

        $mailResult = sendEmail($email, $subject, $body);

        if (!$mailResult['status']) {
            setFlashMessage('error', 'Failed to send email. Please try again.');
            redirect('../../forgot-password.php');
        }
    }

    // Always show success message to prevent user enumeration
    setFlashMessage('success', 'If an account exists, a reset code has been sent.');
    redirect('../../verify-reset.php?email=' . urlencode($email));
}
