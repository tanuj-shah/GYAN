<?php
// config/database.php

// Load environment variables (simple approach for shared hosting)
// For production, consider using vlucas/phpdotenv or move config outside web root

function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
    return true;
}

// Try to load .env file
$envPath = __DIR__ . '/../.env';
loadEnv($envPath);

// Define database credentials from environment or use defaults
// Docker provides these via docker-compose.yml
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'gyan_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Environment setting
define('ENVIRONMENT', getenv('ENVIRONMENT') ?: 'development');

function getDBConnection()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true, // Enable persistent connections
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log error securely
        error_log("Database connection failed: " . $e->getMessage());

        // In production, don't expose details
        if (ENVIRONMENT === 'production') {
            die('Database connection error. Please contact the administrator.');
        } else {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}
