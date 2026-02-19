<?php
// api/auth/login.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/rate_limit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../login.php');
    }

    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        setFlashMessage('error', 'All fields are required');
        redirect('../../login.php');
    }

    // SECURITY: Rate Limiting - prevent brute force attacks
    // Check by IP address - 5 attempts per 15 minutes
    $clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (!checkRateLimit($clientIP, 5, 900)) {
        http_response_code(429); // Too Many Requests
        setFlashMessage('error', 'Too many login attempts. Please try again in 15 minutes.');
        redirect('../../login.php');
    }

    $result = authenticateUser($email, $password);

    if ($result['status']) {
        // Login Success - reset rate limit
        resetRateLimit($clientIP);

        $user = $result['user'];

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $email;
        $_SESSION['last_activity'] = time(); // For session timeout

        // Handle "Remember Me"
        if (isset($_POST['remember_me'])) {
            try {
                // Generate secure token
                $token = bin2hex(random_bytes(32)); // 64 chars

                // Store in DB
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->execute([$token, $user['id']]);

                // Set Cookie (30 days)
                $cookieValue = $user['id'] . ':' . $token;
                $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
                setcookie('remember_me', $cookieValue, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'domain' => '',
                    'secure' => $isSecure,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            } catch (Exception $e) {
                error_log("Remember Me Error: " . $e->getMessage());
            }
        }

        setFlashMessage('success', 'Welcome back!');

        if ($user['role'] === 'admin') {
            redirect('../../admin/index.php');
        } else {
            redirect('../../dashboard.php');
        }
    } else {
        // Failed login - rate limit counter increments automatically
        setFlashMessage('error', $result['message']);

        // SECURITY: Warning for near-lockout
        $remaining = getRemainingAttempts($clientIP, 5, 900);
        if ($remaining <= 2 && $remaining > 0) {
            setFlashMessage('warning', "Warning: You have only $remaining login attempts remaining before temporary lockout.");
        }

        redirect('../../login.php');
    }
}
