<?php
// public/api/vision2035/update_status.php
// Admin endpoint to update proposal status

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
if (empty($_POST['proposal_id']) || empty($_POST['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Proposal ID and status are required.']);
    exit;
}

$proposalId = (int) $_POST['proposal_id'];
$status = $_POST['status'];

// Validate status
$validStatuses = ['Submitted', 'Under review', 'Rejected', 'Check Your Mail'];
if (!in_array($status, $validStatuses)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

// Update status
$result = updateProposalStatus($proposalId, $status, $_SESSION['user_id']);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Proposal status updated successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update status. Please try again.']);
}
