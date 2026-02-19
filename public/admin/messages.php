<?php
require_once __DIR__ . '/header.php';

$pdo = getDBConnection();
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Contact Messages</h1>

<div class="bg-white rounded shadow overflow-hidden">
    <ul class="divide-y divide-gray-200" id="messageList">
        <?php foreach ($messages as $msg): ?>
            <li class="p-6 hover:bg-gray-50 transition-colors" id="msg-<?php echo $msg['id']; ?>">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span id="status-<?php echo $msg['id']; ?>"
                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $msg['status'] == 'new' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo ucfirst($msg['status']); ?>
                        </span>
                        <p class="text-sm font-bold text-gray-900 truncate">
                            <?php echo htmlspecialchars($msg['subject']); ?>
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <?php if ($msg['status'] == 'new'): ?>
                            <button onclick="handleAction(<?php echo $msg['id']; ?>, 'read')"
                                class="text-xs font-medium text-primary hover:text-primary-dark transition-colors">Mark as
                                Read</button>
                        <?php endif; ?>
                        <button onclick="handleAction(<?php echo $msg['id']; ?>, 'delete')"
                            class="text-xs font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <?php echo htmlspecialchars($msg['name']); ?>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>"
                            class="hover:underline text-primary">
                            <?php echo htmlspecialchars($msg['email']); ?>
                        </a>
                    </div>
                </div>

                <div class="text-sm text-gray-600 bg-white p-4 rounded-xl border border-gray-100 italic">
                    "<?php echo nl2br(htmlspecialchars($msg['message'])); ?>"
                </div>
                <div class="mt-4 text-[11px] text-gray-400 text-right">
                    Received on: <?php echo date('M j, Y H:i', strtotime($msg['created_at'])); ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    async function handleAction(id, action) {
        if (action === 'delete' && !confirm('Are you sure you want to delete this message?')) return;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', action);

        try {
            console.log(`Performing ${action} action on message ID: ${id}`);

            const response = await fetch('../api/admin/message_actions.php', {
                method: 'POST',
                body: formData
            });

            console.log(`Response status: ${response.status}`);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error response:', errorText);
                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('Server response:', result);

            if (result.status === 'success') {
                if (action === 'delete') {
                    document.getElementById(`msg-${id}`).remove();
                } else if (action === 'read') {
                    const statusBadge = document.getElementById(`status-${id}`);
                    statusBadge.innerText = 'Read';
                    statusBadge.classList.remove('bg-green-100', 'text-green-800');
                    statusBadge.classList.add('bg-gray-100', 'text-gray-800');
                    // Hide the "Mark as Read" button
                    event.target.remove();
                }
            } else {
                console.error('Action failed:', result.message);
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error in handleAction:', error);
            alert('An error occurred: ' + error.message + '. Please check console for details.');
        }
    }
</script>

</div>
</div>
</body>

</html>