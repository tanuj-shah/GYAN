<?php
// includes/admin.php
require_once __DIR__ . '/../config/database.php';

function checkAdmin()
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        setFlashMessage('error', 'Access Denied: Admins only.');
        redirect('/public/login.php');
    }
}

function getDashboardStats()
{
    $pdo = getDBConnection();

    $stats = [];
    $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $stats['events'] = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $stats['messages'] = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
    $stats['pending_blogs'] = $pdo->query("SELECT COUNT(*) FROM blogs WHERE status = 'pending'")->fetchColumn();
    $stats['pending_blogs'] = $pdo->query("SELECT COUNT(*) FROM blogs WHERE status = 'pending'")->fetchColumn();

    return $stats;
}

function getAllUsers()
{
    $pdo = getDBConnection();
    return $pdo->query("SELECT u.id, u.email, u.role, u.status, u.created_at, p.full_name FROM users u LEFT JOIN profiles p ON u.id = p.user_id ORDER BY u.created_at DESC")->fetchAll();
}

function updateUserStatus($userId, $status)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $userId]);
}

function deleteUser($userId)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        return $stmt->execute([$userId]);
    } catch (PDOException $e) {
        return false;
    }
}

function createEvent($title, $slug, $description, $date, $location, $imageUrl, $registrationUrl = null, $registrationDeadline = null, $postedDate = null, $eventStatus = 'upcoming', $registrationEnabled = false)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO events (title, slug, description, event_date, location, image_url, registration_url, registration_deadline, posted_date, event_status, registration_enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $description, $date, $location, $imageUrl, $registrationUrl, $registrationDeadline, $postedDate ?? date('Y-m-d H:i:s'), $eventStatus, $registrationEnabled]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function updateEvent($id, $title, $slug, $description, $date, $location, $imageUrl, $registrationUrl = null, $registrationDeadline = null, $postedDate = null, $eventStatus = 'upcoming', $registrationEnabled = false)
{
    try {
        $pdo = getDBConnection();
        $sql = "UPDATE events SET title = ?, slug = ?, description = ?, event_date = ?, location = ?";
        $params = [$title, $slug, $description, $date, $location];

        if ($imageUrl) {
            $sql .= ", image_url = ?";
            $params[] = $imageUrl;
        }

        $sql .= ", registration_url = ?, registration_deadline = ?, posted_date = ?, event_status = ?, registration_enabled = ? WHERE id = ?";
        array_push($params, $registrationUrl, $registrationDeadline, $postedDate, $eventStatus, $registrationEnabled, $id);

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        return false;
    }
}



