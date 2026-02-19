<?php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../../dashboard.php');
}

if (!isLoggedIn()) {
    setFlashMessage('error', 'Unauthorized access.');
    redirect('../../login.php');
}

$title = sanitize($_POST['title'] ?? '');
$excerpt = sanitize($_POST['excerpt'] ?? '');
$content = $_POST['content'] ?? ''; // Content might have HTML if using an editor, but we'll sanitize for now or trust it if it's from a trusted source. For safety, we should still handle it carefully.
$csrf_token = $_POST['csrf_token'] ?? '';

if (!verifyCSRFToken($csrf_token)) {
    setFlashMessage('error', 'CSRF token validation failed.');
    redirect('../../blog-create.php');
}

if (empty($title) || empty($content)) {
    setFlashMessage('error', 'Title and content are required.');
    redirect('../../blog-create.php');
}

// Generate Slug
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
// Check for unique slug
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT id FROM blogs WHERE slug = ?");
$stmt->execute([$slug]);
if ($stmt->fetch()) {
    $slug .= '-' . time();
}

$featured_image = null;
if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
    $uploadResult = handleBlogImageUpload($_FILES['featured_image']);
    if ($uploadResult['status']) {
        $featured_image = $uploadResult['path'];
    } else {
        setFlashMessage('error', $uploadResult['message']);
        redirect('../../blog-create.php');
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO blogs (user_id, title, slug, excerpt, content, featured_image, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $slug,
        $excerpt,
        $content,
        $featured_image
    ]);

    setFlashMessage('success', 'Blog submitted successfully! It will be published once approved by an admin.');
    redirect('../../dashboard.php');
} catch (PDOException $e) {
    setFlashMessage('error', 'Database error: ' . $e->getMessage());
    redirect('../../blog-create.php');
}
