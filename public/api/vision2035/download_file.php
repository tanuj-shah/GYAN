<?php
// public/api/vision2035/download_file.php
// Secure file download handler

session_start();
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/vision2035.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    die('Unauthorized. Please login.');
}

// Validate input
if (empty($_GET['proposal_id']) || empty($_GET['file'])) {
    http_response_code(400);
    die('Invalid request.');
}

$proposalId = (int) $_GET['proposal_id'];
$fileName = basename($_GET['file']); // Prevent directory traversal

// Get proposal details
$proposal = getProposalById($proposalId);

if (!$proposal) {
    http_response_code(404);
    die('Proposal not found.');
}

// Check if user owns the proposal OR is admin
if ($proposal['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
    http_response_code(403);
    die('Access denied.');
}

// Get attachments
$attachments = getProposalAttachments($proposalId);
$fileFound = false;

foreach ($attachments as $attachment) {
    if ($attachment['stored_name'] === $fileName) {
        $fileFound = true;
        $originalName = $attachment['original_name'];
        $mimeType = $attachment['mime_type'];
        break;
    }
}

if (!$fileFound) {
    http_response_code(404);
    die('File not found.');
}

// Build file path
$filePath = __DIR__ . '/../../uploads/vision2035/' . $fileName;

// Verify file exists
if (!file_exists($filePath)) {
    http_response_code(404);
    die('File not found on server.');
}

// Set headers for download
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $originalName . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// Output file
readfile($filePath);
exit;
