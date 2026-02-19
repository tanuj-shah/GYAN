<?php
// database/setup.php

require_once __DIR__ . '/../config/database.php';

echo "<h1>Database Setup</h1>";

try {
    // Attempt to connect WITHOUT dbname first to create it if needed
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>";

    // Create database if not exists
    $dbname = DB_NAME;
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database `$dbname` created or already exists.<br>";

    // Now connect to the specific DB
    $pdo = getDBConnection();

    if ($pdo) {
        // Read schema.sql
        $schemaFile = __DIR__ . '/schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);

            // Execute the SQL commands
            // Note: simple splitting by ; might fail if ; is in strings, but for this schema it's fine.
            // A robust way is to execute the whole block if the driver supports multiple queries,
            // or use a library. PDO usually allows multiple queries in one execute call if configured.

            $pdo->exec($sql);
            echo "Schema imported successfully from schema.sql.<br>";

            // Seed Admin User
            $adminEmail = 'admin@gyan.org';
            $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$adminEmail]);
            if (!$check->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role, status) VALUES (?, ?, 'admin', 'active')");
                $stmt->execute([$adminEmail, $adminPass]);
                // Create Admin Profile
                $adminId = $pdo->lastInsertId();
                $stmt = $pdo->prepare("INSERT INTO profiles (user_id, full_name, bio, profession, country, is_public) VALUES (?, 'System Admin', 'Administrator of GYAN Platform', 'Admin', 'Nepal', 0)");
                $stmt->execute([$adminId]);
                echo "Default Admin User Created: <strong>admin@gyan.org</strong> / <strong>admin123</strong><br>";
            }

            echo "<strong>Setup Complete!</strong>";
        } else {
            echo "Error: schema.sql not found.";
        }
    } else {
        echo "Error: Could not connect to the database `$dbname`.";
    }

} catch (PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
