<?php
// api/profile/update.php
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/profile.php';
require_once __DIR__ . '/../../../includes/upload.php';

if (!isLoggedIn()) {
    setFlashMessage('error', 'Unauthorized');
    redirect('../../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid Session Token');
        redirect('../../edit_profile.php');
    }

    $userId = $_SESSION['user_id'];
    $data = [
        'full_name' => sanitize($_POST['name'] ?? ''),
        'bio' => sanitize($_POST['bio'] ?? ''),
        'profession' => sanitize($_POST['profession'] ?? ''),
        'country' => sanitize($_POST['country'] ?? ''),
        'skills' => sanitize($_POST['skills'] ?? ''),
        'is_public' => isset($_POST['is_public']) ? 1 : 0
    ];

    // Handle Social Links (as JSON)
    $social = [
        'facebook' => sanitize($_POST['facebook'] ?? ''),
        'linkedin' => sanitize($_POST['linkedin'] ?? ''),
        'twitter' => sanitize($_POST['twitter'] ?? '')
    ];
    $data['social_links'] = json_encode($social);

    // Handle File Upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadResult = handleProfileImageUpload($_FILES['photo']);
        if ($uploadResult['status']) {
            $data['photo_url'] = $uploadResult['path'];
        } else {
            setFlashMessage('error', $uploadResult['message']);
            redirect('../../edit_profile.php');
        }
    }

    $result = updateProfile($userId, $data);

    if ($result['status']) {
        setFlashMessage('success', 'Profile updated successfully');
        redirect('../../edit_profile.php');
    } else {
        setFlashMessage('error', $result['message']);
        redirect('../../edit_profile.php');
    }
}
