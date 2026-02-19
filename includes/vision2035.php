<?php
// includes/vision2035.php
// Helper functions for Vision 2035 proposal system

require_once __DIR__ . '/../config/database.php';

/**
 * Get all proposals for a specific user
 */
function getUserProposals($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM vision_proposals 
        WHERE user_id = ? 
        ORDER BY submitted_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/**
 * Get a single proposal by ID
 */
function getProposalById($proposalId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT vp.*, u.email, p.full_name 
        FROM vision_proposals vp
        JOIN users u ON vp.user_id = u.id
        LEFT JOIN profiles p ON vp.user_id = p.user_id
        WHERE vp.id = ?
    ");
    $stmt->execute([$proposalId]);
    return $stmt->fetch();
}

/**
 * Get all proposals with optional filtering and sorting (Admin)
 */
function getAllProposals($filters = [])
{
    $pdo = getDBConnection();

    $sql = "
        SELECT vp.*, u.email, p.full_name 
        FROM vision_proposals vp
        JOIN users u ON vp.user_id = u.id
        LEFT JOIN profiles p ON vp.user_id = p.user_id
        WHERE 1=1
    ";

    $params = [];

    // Filter by status
    if (!empty($filters['status']) && $filters['status'] !== 'All') {
        $sql .= " AND vp.status = ?";
        $params[] = $filters['status'];
    }

    // Filter by priority
    if (!empty($filters['priority']) && $filters['priority'] !== 'All') {
        $sql .= " AND vp.priority = ?";
        $params[] = $filters['priority'];
    }

    // Sorting
    $orderBy = "vp.submitted_at DESC"; // Default: newest first
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'oldest':
                $orderBy = "vp.submitted_at ASC";
                break;
            case 'priority':
                $orderBy = "FIELD(vp.priority, 'High', 'Medium', 'Low'), vp.submitted_at DESC";
                break;
            default:
                $orderBy = "vp.submitted_at DESC";
        }
    }

    $sql .= " ORDER BY " . $orderBy;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Update proposal status and create notification
 */
function updateProposalStatus($proposalId, $status, $adminId)
{
    try {
        $pdo = getDBConnection();
        $pdo->beginTransaction();

        // Update proposal
        $stmt = $pdo->prepare("
            UPDATE vision_proposals 
            SET status = ?, reviewed_by = ?, reviewed_at = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$status, $adminId, $proposalId]);

        // Get proposal details for notification
        $proposal = getProposalById($proposalId);

        // Create notification message
        $messages = [
            'Submitted' => 'Your proposal has been marked as submitted.',
            'Under review' => 'Your proposal is now under review by our team.',
            'Rejected' => 'Your proposal has been reviewed. Please check your email for details.',
            'Check Your Mail' => 'Important update on your proposal. Please check your email.'
        ];

        $message = $messages[$status] ?? 'Your proposal status has been updated.';

        // Create notification
        createNotification($proposal['user_id'], $proposalId, $message);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Vision 2035 Status Update Error: " . $e->getMessage());
        return false;
    } catch (Throwable $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Vision 2035 Status Update Generalized Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Create a notification for a user
 */
function createNotification($userId, $proposalId, $message)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO vision_notifications (user_id, proposal_id, message) 
        VALUES (?, ?, ?)
    ");
    return $stmt->execute([$userId, $proposalId, $message]);
}

/**
 * Get notifications for a user
 */
function getUserNotifications($userId, $unreadOnly = false)
{
    $pdo = getDBConnection();

    $sql = "
        SELECT vn.*, vp.title as proposal_title 
        FROM vision_notifications vn
        JOIN vision_proposals vp ON vn.proposal_id = vp.id
        WHERE vn.user_id = ?
    ";

    if ($unreadOnly) {
        $sql .= " AND vn.is_read = 0";
    }

    $sql .= " ORDER BY vn.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/**
 * Get unread notification count
 */
function getUnreadNotificationCount($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM vision_notifications 
        WHERE user_id = ? AND is_read = 0
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

/**
 * Mark notification as read
 */
function markNotificationRead($notificationId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE vision_notifications SET is_read = 1 WHERE id = ?");
    return $stmt->execute([$notificationId]);
}

/**
 * Handle file uploads for proposals
 * Returns JSON array of file metadata or false on error
 */
function handleFileUpload($files)
{
    $uploadDir = __DIR__ . '/../public/uploads/vision2035/';

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowedExtensions = ['pdf', 'xls', 'xlsx', 'csv', 'png', 'jpg', 'jpeg'];
    $allowedMimeTypes = [
        'application/pdf',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'image/png',
        'image/jpeg',
        'image/jpg'
    ];
    $maxFileSize = 10 * 1024 * 1024; // 10MB

    $uploadedFiles = [];

    // Handle multiple files
    if (isset($files['files'])) {
        $fileCount = count($files['files']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            // Skip empty uploads
            if ($files['files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            // Check for upload errors
            if ($files['files']['error'][$i] !== UPLOAD_ERR_OK) {
                return false;
            }

            $fileName = $files['files']['name'][$i];
            $fileTmpName = $files['files']['tmp_name'][$i];
            $fileSize = $files['files']['size'][$i];
            $fileMimeType = mime_content_type($fileTmpName);

            // Validate file size
            if ($fileSize > $maxFileSize) {
                return false;
            }

            // Validate extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                return false;
            }

            // Validate MIME type
            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                return false;
            }

            // Generate unique filename
            $newFileName = uniqid('vision_' . time() . '_', true) . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            // Move uploaded file
            if (move_uploaded_file($fileTmpName, $destination)) {
                $uploadedFiles[] = [
                    'original_name' => $fileName,
                    'stored_name' => $newFileName,
                    'file_size' => $fileSize,
                    'mime_type' => $fileMimeType,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
            } else {
                return false;
            }
        }
    }

    return json_encode($uploadedFiles);
}

/**
 * Get attachments for a proposal
 */
function getProposalAttachments($proposalId)
{
    $proposal = getProposalById($proposalId);
    if ($proposal && !empty($proposal['attachments'])) {
        return json_decode($proposal['attachments'], true);
    }
    return [];
}

/**
 * Validate proposal input data
 */
function validateProposalInput($data)
{
    require_once __DIR__ . '/rich-text-sanitizer.php';

    $errors = [];

    // Title validation
    if (empty($data['title']) || strlen($data['title']) > 255) {
        $errors[] = 'Title is required and must be less than 255 characters.';
    }

    // Category validation
    $validCategories = ['Policy Recommendations', 'Local issue/grievance', 'Technical solution', 'Visionary Concept', 'Other'];
    if (empty($data['category']) || !in_array($data['category'], $validCategories)) {
        $errors[] = 'Please select a valid category.';
    }

    // If category is Other, other_category is required
    if ($data['category'] === 'Other' && empty($data['other_category'])) {
        $errors[] = 'Please specify the category when selecting "Other".';
    }

    // Delegation validation
    $validDelegations = ['Ministry of Finance', 'National Planning Commission', 'Municipality', 'Local Level', 'Other'];
    if (empty($data['delegation']) || !in_array($data['delegation'], $validDelegations)) {
        $errors[] = 'Please select a valid delegation.';
    }

    // If delegation is Other, other_delegation is required
    if ($data['delegation'] === 'Other' && empty($data['other_delegation'])) {
        $errors[] = 'Please specify the delegation when selecting "Other".';
    }

    // Priority validation
    $validPriorities = ['Low', 'Medium', 'High'];
    if (empty($data['priority']) || !in_array($data['priority'], $validPriorities)) {
        $errors[] = 'Please select a valid priority.';
    }

    // Proposal text validation with rich-text support
    if (empty($data['proposal_text'])) {
        $errors[] = 'Proposal text is required.';
    } else {
        // Validate rich-text content (includes sanitization and word count)
        $validation = validateRichTextContent($data['proposal_text'], 600);

        if (!$validation['valid']) {
            $errors[] = $validation['error'];
        } else {
            // Store sanitized and validated content back in data
            $data['proposal_text_sanitized'] = $validation['sanitized'];
            $data['word_count'] = $validation['word_count'];
        }
    }

    return $errors;
}

/**
 * Create a new proposal
 */
function createProposal($userId, $data, $attachments = null)
{
    try {
        $pdo = getDBConnection();

        // Use sanitized text if available from validation, otherwise use original
        $proposalText = isset($data['proposal_text_sanitized'])
            ? $data['proposal_text_sanitized']
            : $data['proposal_text'];

        $stmt = $pdo->prepare("
            INSERT INTO vision_proposals 
            (user_id, title, category, other_category, delegation, other_delegation, priority, proposal_text, attachments) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $data['title'],
            $data['category'],
            $data['other_category'] ?? null,
            $data['delegation'],
            $data['other_delegation'] ?? null,
            $data['priority'],
            $proposalText,  // Use sanitized text
            $attachments
        ]);

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a proposal and its associated files (Admin only)
 */
function deleteProposal($proposalId)
{
    $pdo = getDBConnection();

    try {
        $pdo->beginTransaction();

        // Get proposal to delete attachments
        $stmt = $pdo->prepare("SELECT attachments FROM vision_proposals WHERE id = ?");
        $stmt->execute([$proposalId]);
        $proposal = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($proposal && $proposal['attachments']) {
            // Delete attachment files from filesystem
            $attachments = json_decode($proposal['attachments'], true);
            if (is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    if (isset($attachment['url'])) {
                        $filePath = __DIR__ . '/../public/' . ltrim($attachment['url'], '/');
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
        }

        // Delete notifications related to this proposal
        $stmt = $pdo->prepare("DELETE FROM vision_notifications WHERE proposal_id = ?");
        $stmt->execute([$proposalId]);

        // Delete proposal
        $stmt = $pdo->prepare("DELETE FROM vision_proposals WHERE id = ?");
        $stmt->execute([$proposalId]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Delete proposal error: " . $e->getMessage());
        return false;
    }
}

