<?php
// public/api/vision2035/delete_proposal.php
// Admin endpoint to delete a Vision 2035 proposal

session_start();
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/vision2035.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied. Admin only.']);
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

// Validate input
if (empty($_POST['proposal_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Proposal ID is required.']);
    exit;
}

$proposalId = (int) $_POST['proposal_id'];

// Delete proposal
$result = deleteProposal($proposalId);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Proposal deleted successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete proposal. Please try again.']);
}
