<?php
// public/admin/vision-proposals.php
// Admin panel for reviewing Vision 2035 proposals

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/vision2035.php';

// Get filter parameters
$filters = [
    'status' => $_GET['status'] ?? 'All',
    'priority' => $_GET['priority'] ?? 'All',
    'sort' => $_GET['sort'] ?? 'newest'
];

// Get all proposals with filters
$proposals = getAllProposals($filters);

// Get proposal counts by status
$pdo = getDBConnection();
$statusCounts = [];
$statuses = ['Submitted', 'Under review', 'Rejected', 'Check Your Mail'];
foreach ($statuses as $status) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM vision_proposals WHERE status = ?");
    $stmt->execute([$status]);
    $statusCounts[$status] = $stmt->fetchColumn();
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Vision 2035 Proposals</h1>
    <p class="mt-2 text-gray-600">Review and manage community proposals for Nepal's future</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php
    $statusColors = [
        'Submitted' => ['bg' => 'bg-white border border-gray-200', 'text' => 'text-blue-600', 'badge' => 'bg-blue-600'],
        'Under review' => ['bg' => 'bg-white border border-purple-100', 'text' => 'text-purple-600', 'badge' => 'bg-purple-600'],
        'Rejected' => ['bg' => 'bg-white border border-red-100', 'text' => 'text-red-600', 'badge' => 'bg-red-600'],
        'Check Your Mail' => ['bg' => 'bg-white border border-green-100', 'text' => 'text-green-600', 'badge' => 'bg-green-600']
    ];

    foreach ($statusCounts as $status => $count):
        $colors = $statusColors[$status];
        ?>
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-lg <?php echo $colors['bg']; ?> <?php echo $colors['text']; ?>">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span
                    class="text-xs font-bold <?php echo $colors['text']; ?> <?php echo $colors['bg']; ?> px-2 py-1 rounded-full uppercase tracking-wider">
                    <?php echo $status; ?>
                </span>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">
                <?php echo $count; ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Proposals</p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="All" <?php echo $filters['status'] === 'All' ? 'selected' : ''; ?>>All Statuses</option>
                <option value="Submitted" <?php echo $filters['status'] === 'Submitted' ? 'selected' : ''; ?>>Submitted
                </option>
                <option value="Under review" <?php echo $filters['status'] === 'Under review' ? 'selected' : ''; ?>>Under
                    review</option>
                <option value="Rejected" <?php echo $filters['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected
                </option>
                <option value="Check Your Mail" <?php echo $filters['status'] === 'Check Your Mail' ? 'selected' : ''; ?>>
                    Check Your Mail</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
            <select name="priority"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="All" <?php echo $filters['priority'] === 'All' ? 'selected' : ''; ?>>All Priorities
                </option>
                <option value="High" <?php echo $filters['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                <option value="Medium" <?php echo $filters['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="Low" <?php echo $filters['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
            <select name="sort"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Newest First
                </option>
                <option value="oldest" <?php echo $filters['sort'] === 'oldest' ? 'selected' : ''; ?>>Oldest First
                </option>
                <option value="priority" <?php echo $filters['sort'] === 'priority' ? 'selected' : ''; ?>>Priority (High
                    to Low)</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Proposals Table -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Proposal
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Priority
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Submitted
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($proposals)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-medium">No proposals found</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proposals as $proposal): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">
                                    <?php echo htmlspecialchars($proposal['title']); ?>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <?php echo htmlspecialchars(substr($proposal['proposal_text'], 0, 100)); ?>...
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    <?php echo htmlspecialchars($proposal['full_name'] ?? 'N/A'); ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars($proposal['email']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">
                                    <?php echo htmlspecialchars($proposal['category']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $priorityClasses = [
                                    'Low' => 'bg-white border border-green-100 text-green-800',
                                    'Medium' => 'bg-white border border-yellow-100 text-yellow-800',
                                    'High' => 'bg-white border border-red-100 text-red-800'
                                ];
                                $pClass = $priorityClasses[$proposal['priority']] ?? 'bg-white border border-gray-100 text-gray-800';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $pClass; ?>">
                                    <?php echo htmlspecialchars($proposal['priority']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusClasses = [
                                    'Submitted' => 'bg-white border border-gray-200 text-gray-800',
                                    'Under review' => 'bg-white border border-purple-100 text-purple-800',
                                    'Rejected' => 'bg-white border border-red-100 text-red-800',
                                    'Check Your Mail' => 'bg-white border border-green-100 text-green-800'
                                ];
                                $sClass = $statusClasses[$proposal['status']] ?? 'bg-white border border-gray-100 text-gray-800';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $sClass; ?>">
                                    <?php echo htmlspecialchars($proposal['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($proposal['submitted_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="viewProposal(<?php echo $proposal['id']; ?>)"
                                    class="text-primary hover:text-blue-700 font-medium text-sm mr-3">
                                    View
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Proposal Detail Modal -->
<div id="proposalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Proposal Details</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="modalContent">
                <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    const proposalsData = <?php echo json_encode($proposals); ?>;

    function viewProposal(id) {
        const proposal = proposalsData.find(p => p.id == id);
        if (!proposal) return;

        const attachments = proposal.attachments ? JSON.parse(proposal.attachments) : [];

        let attachmentsHTML = '';
        if (attachments.length > 0) {
            attachmentsHTML = `
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Attachments</h3>
                <div class="space-y-2">
                    ${attachments.map(file => `
                        <a href="../api/vision2035/download_file.php?proposal_id=${proposal.id}&file=${encodeURIComponent(file.stored_name)}" 
                           class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">${file.original_name}</span>
                            <span class="text-xs text-gray-400 ml-auto">${formatFileSize(file.file_size)}</span>
                        </a>
                    `).join('')}
                </div>
            </div>
        `;
        }

        const modalContent = `
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">User</h3>
                <p class="text-gray-700">${proposal.full_name || 'N/A'}</p>
                <p class="text-sm text-gray-500">${proposal.email}</p>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Submitted</h3>
                <p class="text-gray-700">${new Date(proposal.submitted_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Title</h3>
            <p class="text-lg font-bold text-gray-900">${proposal.title}</p>
        </div>
        
        <div class="grid grid-cols-3 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Category</h3>
                <p class="text-gray-700">${proposal.category}</p>
                ${proposal.other_category ? `<p class="text-sm text-gray-500">${proposal.other_category}</p>` : ''}
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Delegation</h3>
                <p class="text-gray-700">${proposal.delegation}</p>
                ${proposal.other_delegation ? `<p class="text-sm text-gray-500">${proposal.other_delegation}</p>` : ''}
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Priority</h3>
                <span class="px-3 py-1 rounded-full text-xs font-bold ${getPriorityClass(proposal.priority)}">${proposal.priority}</span>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Proposal</h3>
            <div class="bg-white border border-gray-100 rounded-lg p-4 text-gray-700 leading-relaxed whitespace-pre-wrap">${proposal.proposal_text}</div>
        </div>
        
        ${attachmentsHTML}
        
        <div class="border-t border-gray-200 pt-6 space-y-4">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Actions</h3>
            
            <!-- Download Proposal Button -->
            <div class="mb-4">
                <a href="../api/vision2035/download_proposal.php?proposal_id=${proposal.id}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download as PDF
                </a>
                <p class="text-xs text-gray-500 mt-2">💡 Opens in new tab → Press Ctrl+P → Save as PDF</p>
            </div>
            
            <!-- Delete Proposal Button -->
            <div class="mb-4">
                <button onclick="deleteProposal(${proposal.id})" 
                   class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Proposal
                </button>
                <p class="text-xs text-red-500 mt-2">⚠️ This action cannot be undone</p>
            </div>
            
            <!-- Update Status Form -->
            <div>
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Update Status</h4>
                <form onsubmit="updateStatus(event, ${proposal.id})" class="flex gap-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="Submitted" ${proposal.status === 'Submitted' ? 'selected' : ''}>Submitted</option>
                        <option value="Under review" ${proposal.status === 'Under review' ? 'selected' : ''}>Under review</option>
                        <option value="Rejected" ${proposal.status === 'Rejected' ? 'selected' : ''}>Rejected</option>
                        <option value="Check Your Mail" ${proposal.status === 'Check Your Mail' ? 'selected' : ''}>Check Your Mail</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    `;

        document.getElementById('modalContent').innerHTML = modalContent;
        document.getElementById('proposalModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('proposalModal').classList.add('hidden');
    }

    function getPriorityClass(priority) {
        const classes = {
            'Low': 'bg-white border border-green-100 text-green-800',
            'Medium': 'bg-white border border-yellow-100 text-yellow-800',
            'High': 'bg-white border border-red-100 text-red-800'
        };
        return classes[priority] || 'bg-white border border-gray-100 text-gray-800';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    async function updateStatus(event, proposalId) {
        event.preventDefault();
        console.log('updateStatus called for proposal ID:', proposalId    
        );
        
        const formData = new FormData(event.target);
        formData.append('proposal_id', proposalId);
        
        // Log FormData contents
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }

        try {
            console.log('Sending request to ../api/vision2035/update_status.php');
            const response = await fetch('../api/vision2035/update_status.php', {
                method: 'POST',
                body: formData
            });
            
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);

            const result = await response.json();
            console.log('Response data:', result);

            if (result.success) {
                alert('Status updated successfully!');
                location.reload();
            } else {
                console.error('Update failed:', result.message);
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Update status error:', error);
            alert('Failed to update status. Please try again.');
        }
    }

    async function deleteProposal(proposalId) {
        if (!confirm('Are you sure you want to delete this proposal? This action cannot be undone.')) {
            return;
        }
        
        console.log('deleteProposal called for proposal ID:', proposalId);
        
        const formData = new FormData();
        formData.append('proposal_id', proposalId);
        formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');
        
        try {
            console.log('Sending delete request to ../api/vision2035/delete_proposal.php');
            const response = await fetch('../api/vision2035/delete_proposal.php', {
                method: 'POST',
                body: formData
            });
            
            console.log('Delete response status:', response.status);
            const result = await response.json();
            console.log('Delete response data:', result);
            
            if (result.success) {
                alert('Proposal deleted successfully!');
                location.reload();
            } else {
                console.error('Delete failed:', result.message);
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Delete proposal error:', error);
            alert('Failed to delete proposal. Please try again.');
        }
    }


    // Close modal on outside click
    document.getElementById('proposalModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

</div>
</div>
</body>

</html>