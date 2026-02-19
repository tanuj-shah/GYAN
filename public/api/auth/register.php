<?php
// public/api/auth/register.php
// Register endpoint with OTP

require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../register.php');
    }

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        setFlashMessage('error', 'All fields are required');
        redirect('../../register.php');
    }

    if ($password !== $confirm) {
        setFlashMessage('error', 'Passwords do not match');
        redirect('../../register.php');
    }

    if (strlen($password) < 6) {
        setFlashMessage('error', 'Password must be at least 6 characters');
        redirect('../../register.php');
    }

    // Attempt Register
    $result = registerUser($name, $email, $password);

    if ($result['status']) {
        // Redirect to OTP Verification page
        setFlashMessage('success', 'Account created! Please check your email for the verification code.');
        redirect('../../verify-account.php?email=' . urlencode($email));
    } else {
        setFlashMessage('error', $result['message']);
        redirect('../../register.php');
    }
}
