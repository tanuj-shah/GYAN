<?php
// includes/events.php
require_once __DIR__ . '/../config/database.php';

function getEvents($limit = 10, $offset = 0, $status = null, $registrationEnabled = null)
{
    $pdo = getDBConnection();
    $sql = "SELECT * FROM events";
    $params = [];
    $whereClauses = [];

    if ($status) {
        $whereClauses[] = "event_status = ?";
        $params[] = $status;
    }

    if ($registrationEnabled !== null) {
        $whereClauses[] = "registration_enabled = ?";
        $params[] = $registrationEnabled;
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Default sort
    if ($status === 'past') {
        $sql .= " ORDER BY event_date DESC LIMIT ? OFFSET ?";
    } else {
        // Upcoming usually wants soonest first
        $sql .= " ORDER BY event_date ASC LIMIT ? OFFSET ?";
    }

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $val) {
        $stmt->bindValue($key + 1, $val);
    }

    // Bind LIMIT and OFFSET
    $stmt->bindValue(count($params) + 1, (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(count($params) + 2, (int) $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll();
}

function getEventBySlug($slug)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM events WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getEventGallery($eventId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE event_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$eventId]);
    return $stmt->fetchAll();
}

function updateEventStatuses()
{
    $pdo = getDBConnection();
    $now = date('Y-m-d H:i:s');

    // Update based on registration deadline
    $sql1 = "UPDATE events SET event_status = 'past' WHERE event_status = 'upcoming' AND registration_deadline IS NOT NULL AND registration_deadline < ?";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$now]);
    $count1 = $stmt1->rowCount();

    // Fallback: Update based on event_date if no deadline or deadline > event_date (sanity check, usually deadline <= event_date)
    // Actually prompt says: "If registration_deadline is NULL, use event_date"
    $sql2 = "UPDATE events SET event_status = 'past' WHERE event_status = 'upcoming' AND registration_deadline IS NULL AND event_date < ?";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$now]);
    $count2 = $stmt2->rowCount();

    return $count1 + $count2;
}
