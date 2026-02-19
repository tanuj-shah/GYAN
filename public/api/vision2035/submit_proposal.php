<?php
// public/api/vision2035/submit_proposal.php
// Handles proposal submission with file uploads

session_start();
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/vision2035.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login.']);
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

// Validate input
$errors = validateProposalInput($_POST);
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Handle file uploads
$attachments = null;
if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
    $attachments = handleFileUpload($_FILES);
    if ($attachments === false) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'File upload failed. Please check file size (max 10MB) and type (PDF, Excel, CSV, or images only).']);
        exit;
    }
}

// Create proposal
$proposalId = createProposal($_SESSION['user_id'], $_POST, $attachments);

if ($proposalId) {
    setFlashMessage('success', 'Your proposal has been submitted successfully! We will review it soon.');

    // Check if redirect is requested (form submission)
    if (isset($_POST['redirect'])) {
        header('Location: ../../vision-2035.php');
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Proposal submitted successfully.', 'proposal_id' => $proposalId]);
} else {
    // Check if redirect is requested
    if (isset($_POST['redirect'])) {
        setFlashMessage('error', 'Failed to submit proposal. Please try again.');
        header('Location: ../../vision-2035.php');
        exit;
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to submit proposal. Please try again.']);
}
