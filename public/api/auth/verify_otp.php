<?php
// public/api/auth/verify_otp.php
// Endpoint to verify OTP code

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        $email = $_POST['email'] ?? '';
        redirect('../../verify-account.php?email=' . urlencode($email));
    }

    $email = sanitize($_POST['email'] ?? '');
    $otp = sanitize($_POST['otp'] ?? '');

    // Validation
    if (empty($email) || empty($otp)) {
        setFlashMessage('error', 'Email and OTP are required');
        redirect('../../verify-account.php?email=' . urlencode($email));
    }

    // Verify OTP
    $result = verifyOTP($email, $otp);

    if ($result['status']) {
        setFlashMessage('success', $result['message']);
        redirect('../../login.php');
    } else {
        setFlashMessage('error', $result['message']);
        redirect('../../verify-account.php?email=' . urlencode($email));
    }
}
