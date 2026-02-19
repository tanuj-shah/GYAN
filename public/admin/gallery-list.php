<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = getDBConnection();
$events = $pdo->query("SELECT e.*, COUNT(gi.id) as count, (SELECT filename FROM gallery_images WHERE event_id = e.id ORDER BY is_featured DESC LIMIT 1) as cover FROM gallery_events e LEFT JOIN gallery_images gi ON e.id = gi.event_id GROUP BY e.id ORDER BY e.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="px-6 py-12" x-data="{
    deleting: null,
    async deleteEvent(id) {
        if (!confirm('Are you sure you want to delete this entire album and ALL its photos? This cannot be undone.')) return;
        
        this.deleting = id;
        const formData = new FormData();
        formData.append('event_id', id);
        formData.append('csrf_token', '<?php echo generateCSRFToken(); ?>');

        try {
            const response = await fetch('../api/admin/delete_gallery_event.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Deletion failed');
            }
        } catch (err) {
            alert('An error occurred during deletion');
        } finally {
            this.deleting = null;
        }
    }
}">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Gallery Albums</h1>
                <p class="text-gray-500 mt-2 font-medium">Manage your event collections</p>
            </div>
            <a href="gallery-upload.php"
                class="bg-[#DC143C] hover:bg-[#B01030] text-white font-bold py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all uppercase tracking-widest text-sm flex items-center gap-2">
                <span>+ Upload Photos</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($events as $evt): ?>
                <div
                    class="bg-white rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden group">
                    <div class="relative aspect-video bg-gray-100">
                        <?php if ($evt['cover']): ?>
                            <img src="../uploads/gallery/<?php echo $evt['id']; ?>/<?php echo $evt['cover']; ?>"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-400 font-bold">Empty Album</div>
                        <?php endif; ?>


                        <div
                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                            <a href="gallery-upload.php?event_id=<?php echo $evt['id']; ?>"
                                class="bg-white text-blue-600 p-3 rounded-full hover:bg-blue-50 transition-colors shadow-lg"
                                title="Add More Photos">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <a href="gallery.php?event_id=<?php echo $evt['id']; ?>"
                                class="bg-white text-indigo-600 p-3 rounded-full hover:bg-indigo-50 transition-colors shadow-lg"
                                title="Manage Photos">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </a>
                            <button @click="deleteEvent(<?php echo $evt['id']; ?>)"
                                class="bg-white text-red-600 p-3 rounded-full hover:bg-red-50 transition-colors shadow-lg"
                                title="Delete Album">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2 truncate">
                            <?php echo htmlspecialchars($evt['title']); ?>
                        </h3>
                        <div class="flex justify-between items-center">
                            <span
                                class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"><?php echo $evt['count']; ?>
                                Photos</span>
                            <span
                                class="text-xs text-gray-400 font-bold uppercase"><?php echo date('M d, Y', strtotime($evt['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>