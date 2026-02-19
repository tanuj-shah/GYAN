<?php
// public/api/vision2035/download_proposal.php
// Admin endpoint to download complete proposal as HTML file
// Can be converted to PDF using browser print function (File → Print → Save as PDF)

session_start();
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/vision2035.php';
require_once __DIR__ . '/../../../includes/rich-text-sanitizer.php';

// Check if user is admin
if (!isAdmin()) {
    http_response_code(403);
    die('Access denied. Admin only.');
}

// Validate proposal ID
if (empty($_GET['proposal_id'])) {
    http_response_code(400);
    die('Proposal ID is required.');
}

$proposalId = (int) $_GET['proposal_id'];

// Get complete proposal data
$proposal = getProposalById($proposalId);

if (!$proposal) {
    http_response_code(404);
    die('Proposal not found.');
}

// Get attachments
$attachments = getProposalAttachments($proposalId);

// Generate filename
$filename = 'vision-proposal-' . $proposalId . '.html';

// Set headers for download
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Sanitize and display proposal text
$proposalTextDisplay = displayRichTextContent($proposal['proposal_text']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vision 2035 Proposal #
        <?php echo $proposalId; ?>
    </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3B82F6;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3B82F6;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 28px;
            color: #1F2937;
            margin-bottom: 10px;
        }

        .proposal-id {
            font-size: 14px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .section-content {
            background: #F9FAFB;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #3B82F6;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-high {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-low {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-submitted {
            background: #E0E7FF;
            color: #3730A3;
        }

        .badge-review {
            background: #E9D5FF;
            color: #6B21A8;
        }

        .badge-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-approved {
            background: #D1FAE5;
            color: #065F46;
        }

        .proposal-text {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            line-height: 1.8;
        }

        .proposal-text p {
            margin-bottom: 15px;
        }

        .proposal-text ul,
        .proposal-text ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .attachments {
            list-style: none;
        }

        .attachment-item {
            background: #FFFFFF;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .attachment-name {
            font-weight: 500;
            color: #1F2937;
        }

        .attachment-size {
            font-size: 12px;
            color: #6B7280;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
        }

        .print-note {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .print-note strong {
            color: #92400E;
        }

        @media print {
            .print-note {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-note">
        <strong>💡 To convert to PDF:</strong> Use your browser's print function (File → Print → Save as PDF)
    </div>

    <div class="header">
        <div class="logo">GYAN - Global Youth Alliance for Nepal</div>
        <h1>Vision 2035 Proposal</h1>
        <div class="proposal-id">Proposal #
            <?php echo $proposalId; ?>
        </div>
    </div>

    <!-- User Information -->
    <div class="section">
        <div class="section-title">Submitted By</div>
        <div class="section-content">
            <strong>
                <?php echo htmlspecialchars($proposal['full_name'] ?? 'N/A'); ?>
            </strong><br>
            <a href="mailto:<?php echo htmlspecialchars($proposal['email']); ?>">
                <?php echo htmlspecialchars($proposal['email']); ?>
            </a><br>
            <small style="color: #6B7280;">Submitted on:
                <?php echo date('F j, Y \a\t g:i A', strtotime($proposal['submitted_at'])); ?>
            </small>
        </div>
    </div>

    <!-- Title -->
    <div class="section">
        <div class="section-title">Proposal Title</div>
        <div class="section-content">
            <strong style="font-size: 18px;">
                <?php echo htmlspecialchars($proposal['title']); ?>
            </strong>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid">
        <div class="section">
            <div class="section-title">Category</div>
            <div class="section-content">
                <?php echo htmlspecialchars($proposal['category']); ?>
                <?php if (!empty($proposal['other_category'])): ?>
                    <br><small style="color: #6B7280;">
                        <?php echo htmlspecialchars($proposal['other_category']); ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Target Delegation</div>
            <div class="section-content">
                <?php echo htmlspecialchars($proposal['delegation']); ?>
                <?php if (!empty($proposal['other_delegation'])): ?>
                    <br><small style="color: #6B7280;">
                        <?php echo htmlspecialchars($proposal['other_delegation']); ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Priority Level</div>
            <div class="section-content">
                <?php
                $priorityClass = 'badge-medium';
                if ($proposal['priority'] === 'High')
                    $priorityClass = 'badge-high';
                elseif ($proposal['priority'] === 'Low')
                    $priorityClass = 'badge-low';
                ?>
                <span class="badge <?php echo $priorityClass; ?>">
                    <?php echo htmlspecialchars($proposal['priority']); ?>
                </span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Status</div>
            <div class="section-content">
                <?php
                $statusClass = 'badge-submitted';
                if ($proposal['status'] === 'Under review')
                    $statusClass = 'badge-review';
                elseif ($proposal['status'] === 'Rejected')
                    $statusClass = 'badge-rejected';
                elseif ($proposal['status'] === 'Check Your Mail')
                    $statusClass = 'badge-approved';
                ?>
                <span class="badge <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($proposal['status']); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Proposal Text -->
    <div class="section">
        <div class="section-title">Proposal Details</div>
        <div class="proposal-text">
            <?php echo $proposalTextDisplay; ?>
        </div>
    </div>

    <!-- Attachments -->
    <?php if (!empty($attachments)): ?>
        <div class="section">
            <div class="section-title">Supporting Documents (
                <?php echo count($attachments); ?>)
            </div>
            <ul class="attachments">
                <?php foreach ($attachments as $file): ?>
                    <li class="attachment-item">
                        <span class="attachment-name">
                            <?php echo htmlspecialchars($file['original_name']); ?>
                        </span>
                        <span class="attachment-size">
                            <?php
                            $size = $file['file_size'];
                            echo $size < 1024 ? $size . ' B' : ($size < 1048576 ? round($size / 1024, 2) . ' KB' : round($size / 1048576, 2) . ' MB');
                            ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p style="color: #6B7280; font-size: 14px; margin-top: 10px;">
                <em>Note: Attachments can be downloaded separately from the admin panel.</em>
            </p>
        </div>
    <?php endif; ?>

    <!-- Review Information -->
    <?php if (!empty($proposal['reviewed_by']) && !empty($proposal['reviewed_at'])): ?>
        <div class="section">
            <div class="section-title">Review Information</div>
            <div class="section-content">
                Reviewed on:
                <?php echo date('F j, Y \a\t g:i A', strtotime($proposal['reviewed_at'])); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>GYAN - Global Youth Alliance for Nepal | Vision 2035 Initiative</p>
        <p>Document generated on:
            <?php echo date('F j, Y \a\t g:i A'); ?>
        </p>
    </div>
</body>

</html>