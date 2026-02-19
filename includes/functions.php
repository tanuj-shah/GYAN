<?php
// includes/functions.php

// =====================================================
// SECURE SESSION CONFIGURATION
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    // Determine if HTTPS is being used
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

    // Cookie parameters - Secure by default
    session_set_cookie_params([
        'lifetime' => 0,              // Session cookie (expires on browser close)
        'path' => '/',                // Available for entire domain
        'domain' => '',               // Current domain
        'secure' => $isSecure,        // HTTPS only (when available)
        'httponly' => true,           // Prevent JavaScript access
        'samesite' => 'Strict'        // CSRF protection
    ]);

    session_start();

    // Session timeout check (30 minutes of inactivity) - ONLY for logged in users
    if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
        $inactiveTime = time() - $_SESSION['last_activity'];
        $timeoutDuration = 1800; // 30 minutes

        if ($inactiveTime > $timeoutDuration) {
            // Logged in and timed out
            session_unset();     // Unset $_SESSION variable for the run-time 
            session_destroy();   // Destroy session data in storage

            // Redirect to login with timeout message
            // Use header() directly, do not rely on functions defined later
            header('Location: /IRD/public/login.php?timeout=1');
            exit;
        }
    }

    // Update last activity time for logged-in users
    if (isset($_SESSION['user_id'])) {
        $_SESSION['last_activity'] = time();
    }
}

// =====================================================
// AUTO-LOGIN (Remember Me)
// =====================================================
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    // Defer execution to avoid blocking page load if DB is slow
    // We only run this if we are not already logged in
    checkRememberMe();
}

function checkRememberMe()
{
    try {
        if (!isset($_COOKIE['remember_me']))
            return;

        $parts = explode(':', $_COOKIE['remember_me']);
        if (count($parts) !== 2)
            return;

        $userId = (int) $parts[0];
        $token = $parts[1];

        // Safely get DB connection
        if (!function_exists('getDBConnection')) {
            $dbPath = __DIR__ . '/../config/database.php';
            if (file_exists($dbPath)) {
                require_once $dbPath;
            } else {
                return; // Fail silently if DB config missing
            }
        }

        if (function_exists('getDBConnection')) {
            $pdo = getDBConnection();
            // Check if user exists and token matches
            $stmt = $pdo->prepare("SELECT id, role, status, remember_token FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if ($user && !empty($user['remember_token']) && hash_equals($user['remember_token'], $token) && $user['status'] === 'active') {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();
            }
        }
    } catch (Exception $e) {
        // Fail silently - do not break the site if remember me fails
        // user will just stay logged out
        error_log("Remember Me Error: " . $e->getMessage());
    }
}

// =====================================================
// HELPER FUNCTIONS
// =====================================================

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getUserId()
{
    return $_SESSION['user_id'] ?? null;
}

function getUserRole()
{
    return $_SESSION['role'] ?? 'user';
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// =====================================================
// FLASH MESSAGES
// =====================================================

function setFlashMessage($type, $message)
{
    $_SESSION['flash_' . $type] = $message;
}

function getFlashMessage($type)
{
    if (isset($_SESSION['flash_' . $type])) {
        $message = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $message;
    }
    return null;
}

function hasFlashMessage($type)
{
    return isset($_SESSION['flash_' . $type]);
}

// =====================================================
// CSRF PROTECTION
// =====================================================

function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// =====================================================
// SECURITY HELPERS
// =====================================================

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Backward compatibility wrapper
function sanitize($data)
{
    return sanitizeInput($data);
}

function redirectTo($url)
{
    header("Location: $url");
    exit;
}

// Backward compatibility wrapper
function redirect($url)
{
    redirectTo($url);
}

// =====================================================
// STRING HELPERS
// =====================================================

/**
 * Generate a URL-friendly slug from a string
 * @param string $text The text to slugify
 * @return string The slugified text
 */
function slugify($text)
{
    // Replace non-letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Trim
    $text = trim($text, '-');

    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // Lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
