<?php
// includes/auth.php
// Modified to include OTP logic

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/mail.php'; // Include mail helper

/**
 * Generate a 6-digit OTP code
 */
function generateOTP()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Register a new user with OTP verification
 */
function registerUser($name, $email, $password)
{
    try {
        $pdo = getDBConnection();
        // Normalize email
        $email = strtolower(trim($email));

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['status' => false, 'message' => 'Email already registered'];
        }

        $pdo->beginTransaction();

        // 1. Create User (Pending Status)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $otp = generateOTP();
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role, status, otp_code, otp_expiry) VALUES (?, ?, 'user', 'pending', ?, ?)");
        $stmt->execute([$email, $hash, $otp, $expiry]);
        $userId = $pdo->lastInsertId();

        // 2. Create Profile
        $stmt = $pdo->prepare("INSERT INTO profiles (user_id, full_name, is_public) VALUES (?, ?, 1)");
        $stmt->execute([$userId, $name]);

        // 3. Send OTP Email
        $subject = "Verify your GYAN Account";
        $body = "
            <h2>Welcome to GYAN!</h2>
            <p>Your verification code is: <strong>$otp</strong></p>
            <p>This code will expire in 15 minutes.</p>
        ";

        $mailResult = sendEmail($email, $subject, $body);

        if (!$mailResult['status']) {
            // Log error but don't fail registration completely? 
            // Better to rollback if email fails so user can try again
            throw new Exception("Failed to send verification email: " . $mailResult['message']);
        }

        $pdo->commit();
        return ['status' => true, 'message' => 'Account created! Please verify your email.'];

    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

function authenticateUser($email, $password)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, password_hash, role, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['status'] === 'pending') {
            return ['status' => false, 'message' => 'Account not verified. Please check your email for OTP.', 'redirect' => 'verify-account.php?email=' . urlencode($email)];
        }
        if ($user['status'] !== 'active') {
            return ['status' => false, 'message' => 'Account is ' . $user['status']];
        }
        return ['status' => true, 'user' => $user];
    }
    return ['status' => false, 'message' => 'Invalid email or password'];
}

/**
 * Verify OTP code
 */
function verifyOTP($email, $otp)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, otp_code, otp_expiry FROM users WHERE email = ? AND status = 'pending'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        return ['status' => false, 'message' => 'User not found or already verified'];
    }

    if ($user['otp_code'] !== $otp) {
        return ['status' => false, 'message' => 'Invalid verification code'];
    }

    if (strtotime($user['otp_expiry']) < time()) {
        return ['status' => false, 'message' => 'Verification code expired'];
    }

    // Activate Account
    $stmt = $pdo->prepare("UPDATE users SET status = 'active', otp_code = NULL, otp_expiry = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);

    return ['status' => true, 'message' => 'Account verified successfully!'];
}
