<?php
// public/api/auth/logout.php
require_once __DIR__ . '/../../../includes/functions.php';

// Initialize the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear Remember Me Cookie & DB Token
if (isset($_SESSION['user_id'])) {
    try {
        // We need DB connection. Check if included. 
        // functions.php is included, but it might not include database.php
        if (!function_exists('getDBConnection')) {
            $dbPath = __DIR__ . '/../../../config/database.php';
            if (file_exists($dbPath))
                require_once $dbPath;
        }

        if (function_exists('getDBConnection')) {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
    } catch (Exception $e) {
        // Ignore DB error on logout
    }
}

// Clear Remember Me Cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/', '', false, true);
    unset($_COOKIE['remember_me']);
}

// Unset all of the session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
session_start(); // Restart session to set flash message
session_regenerate_id(true);

// Set success message
setFlashMessage('success', 'You have been logged out successfully.');

// Redirect to login page
header("Location: ../../../public/login.php");
exit;
