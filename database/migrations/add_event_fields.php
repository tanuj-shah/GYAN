<?php
require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = getDBConnection();
    echo "Connected to database.\n";

    // Add registration_url
    try {
        $pdo->exec("ALTER TABLE events ADD COLUMN registration_url VARCHAR(500) NULL");
        echo "Added registration_url column.\n";
    } catch (PDOException $e) {
        echo "registration_url column likely exists or error: " . $e->getMessage() . "\n";
    }

    // Add posted_date
    try {
        $pdo->exec("ALTER TABLE events ADD COLUMN posted_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        echo "Added posted_date column.\n";
    } catch (PDOException $e) {
        echo "posted_date column likely exists or error: " . $e->getMessage() . "\n";
    }

    // Add registration_deadline
    try {
        $pdo->exec("ALTER TABLE events ADD COLUMN registration_deadline DATETIME NULL");
        echo "Added registration_deadline column.\n";
    } catch (PDOException $e) {
        echo "registration_deadline column likely exists or error: " . $e->getMessage() . "\n";
    }

    // Add event_status
    try {
        $pdo->exec("ALTER TABLE events ADD COLUMN event_status ENUM('upcoming', 'past') DEFAULT 'upcoming'");
        echo "Added event_status column.\n";
    } catch (PDOException $e) {
        echo "event_status column likely exists or error: " . $e->getMessage() . "\n";
    }

    // Add registration_enabled
    try {
        $pdo->exec("ALTER TABLE events ADD COLUMN registration_enabled BOOLEAN DEFAULT FALSE");
        echo "Added registration_enabled column.\n";
    } catch (PDOException $e) {
        echo "registration_enabled column likely exists or error: " . $e->getMessage() . "\n";
    }

    // Add indexes
    try {
        $pdo->exec("CREATE INDEX idx_registration_deadline ON events(registration_deadline)");
        $pdo->exec("CREATE INDEX idx_event_status ON events(event_status)");
        echo "Added indexes.\n";
    } catch (PDOException $e) {
        echo "Indexes likely exist or error.\n";
    }

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    die("Global Migration Error: " . $e->getMessage());
}
