<?php
// api/admin/create_event.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/admin.php';
require_once __DIR__ . '/../../../includes/upload.php';

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../admin/events.php');
    }

    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $date = $_POST['event_date'];
    $location = sanitize($_POST['location'] ?? '');
    $registrationUrl = !empty($_POST['registration_url']) ? sanitize($_POST['registration_url']) : null;
    $registrationDeadline = !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : null;
    $postedDate = !empty($_POST['posted_date']) ? $_POST['posted_date'] : date('Y-m-d H:i:s');
    $registrationEnabled = isset($_POST['registration_enabled']) ? 1 : 0;

    $slug = slugify($title);

    // Check if slug already exists and make unique
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetchColumn() > 0) {
        $slug .= '-' . substr(md5(time()), 0, 5);
    }

    $imageUrl = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = handleEventImageUpload($_FILES['image']);
        if ($uploadResult['status']) {
            $imageUrl = $uploadResult['path'];
        } else {
            setFlashMessage('error', 'Image Upload Error: ' . $uploadResult['message']);
            redirect('../../admin/events.php');
        }
    }

    // Determine status based on deadline or default to 'upcoming'
    $eventStatus = 'upcoming';
    if ($registrationDeadline && strtotime($registrationDeadline) < time()) {
        $eventStatus = 'past';
    }

    if (createEvent($title, $slug, $description, $date, $location, $imageUrl, $registrationUrl, $registrationDeadline, $postedDate, $eventStatus, $registrationEnabled)) {
        setFlashMessage('success', 'Event created successfully');
    } else {
        setFlashMessage('error', 'Failed to create event');
    }

    redirect('../../admin/events.php');
}

