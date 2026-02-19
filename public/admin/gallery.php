<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/events.php';

$events = getEvents(100); // Get events for dropdown

$pdo = getDBConnection();
$eventId = $_GET['event_id'] ?? null;
$eventFilterTitle = null;

if ($eventId) {
    $stmt = $pdo->prepare("SELECT gi.id, gi.event_id, gi.filename, gi.alt_text, e.title as event_title 
                          FROM gallery_images gi 
                          LEFT JOIN gallery_events e ON gi.event_id = e.id 
                          WHERE gi.event_id = ? 
                          ORDER BY gi.created_at DESC");
    $stmt->execute([$eventId]);
    $groupedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get event title for header
    $stmt = $pdo->prepare("SELECT title FROM gallery_events WHERE id = ?");
    $stmt->execute([$eventId]);
    $eventFilterTitle = $stmt->fetchColumn();
} else {
    $stmt = $pdo->query("
        SELECT gi.id, gi.event_id, gi.filename, gi.alt_text, e.title as event_title 
        FROM gallery_images gi 
        LEFT JOIN gallery_events e ON gi.event_id = e.id 
        ORDER BY e.created_at DESC, gi.created_at DESC
    ");
    $groupedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Manual grouping by event title
$allImages = [];
foreach ($groupedResults as $row) {
    $eventTitle = $row['event_title'] ?: 'Uncategorized';
    if (!isset($allImages[$eventTitle])) {
        $allImages[$eventTitle] = [];
    }
    $allImages[$eventTitle][] = $row;
}
?>

<div x-data="{ 
    deleting: null,
    async deleteImage(id) {
        if (!confirm('Are you sure you want to delete this image?')) return;
        
        this.deleting = id;
        const formData = new FormData();
        formData.append('image_id', id);
        formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>');

        try {
            const response = await fetch('../api/admin/delete_gallery_image.php', {
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
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">
                <?php echo $eventFilterTitle ? htmlspecialchars($eventFilterTitle) . ' Photos' : 'Internal Gallery'; ?>
            </h1>
            <?php if ($eventFilterTitle): ?>
                <a href="gallery-list.php" class="text-indigo-600 font-bold text-xs uppercase hover:underline">&larr; Back to Albums</a>
            <?php endif; ?>
        </div>
        <a href="gallery-upload.php<?php echo $eventId ? '?event_id=' . $eventId : ''; ?>"
            class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-xl transition-all shadow-lg hover:shadow-primary/30 uppercase text-sm">
            Upload New Photos
        </a>
    </div>

    <!-- Grouped Gallery Sections -->
    <div class="space-y-12">
        <?php if (empty($allImages)): ?>
            <div class="bg-white rounded-[2rem] p-12 text-center border border-dashed border-gray-200">
                <p class="text-gray-400 italic">No gallery images found. Start by uploading some photos!</p>
            </div>
        <?php else: ?>
            <?php foreach ($allImages as $eventTitle => $images): ?>
                <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                            <?php echo htmlspecialchars($eventTitle); ?>
                            <span
                                class="ml-2 px-3 py-1 bg-white rounded-full text-[10px] text-gray-400 border border-gray-100"><?php echo count($images); ?>
                                Photos</span>
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                            <?php foreach ($images as $img): ?>
                                <div
                                    class="group relative aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 transition-all hover:shadow-xl">
                                    <img src="../uploads/gallery/<?php echo $img['event_id']; ?>/<?php echo htmlspecialchars($img['filename']); ?>"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        loading="lazy">

                                    <!-- Overlay Actions -->
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                                        <button @click="deleteImage(<?php echo $img['id']; ?>)"
                                            class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300"
                                            :disabled="deleting === <?php echo $img['id']; ?>">
                                            <template x-if="deleting !== <?php echo $img['id']; ?>">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </template>
                                            <template x-if="deleting === <?php echo $img['id']; ?>">
                                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </template>
                                        </button>
                                    </div>

                                    <?php if (!empty($img['alt_text'])): ?>
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
                                            <p class="text-[10px] text-white font-bold truncate">
                                                <?php echo htmlspecialchars($img['alt_text']); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    </div>
</div>
</div>
</body>

</html>