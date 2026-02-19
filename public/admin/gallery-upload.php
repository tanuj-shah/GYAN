<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = getDBConnection();
// Get events already in gallery
$galleryEvents = $pdo->query("SELECT id, title FROM gallery_events ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
// Get main events that are NOT yet in gallery_events (or just all for selection)
$mainEvents = $pdo->query("SELECT id, title FROM events ORDER BY event_date DESC")->fetchAll(PDO::FETCH_ASSOC);

// Create a combined list of suggestions
$eventSuggestions = [];
foreach ($mainEvents as $me) {
    // Check if this title already exists in gallery events
    $exists = false;
    foreach ($galleryEvents as $ge) {
        if ($ge['title'] === $me['title']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $eventSuggestions[] = $me['title'];
    }
}
?>

<div class="max-w-5xl mx-auto px-6 py-12" x-data="galleryUploader()">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Upload Gallery</h1>
            <p class="text-gray-500 mt-1">Add photos to an event album</p>
        </div>
        <a href="gallery-list.php"
            class="text-gray-500 hover:text-blue-600 font-bold text-sm uppercase flex items-center gap-2">
            Back to List
        </a>
    </div>

    <!-- Upload Card -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <form @submit.prevent="submitUpload" class="p-10 space-y-8">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

            <!-- Event Select -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Select Event</label>
                    <div class="relative">
                        <select x-model="eventId"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-lg font-bold rounded-xl px-4 py-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none transition-all">
                            <option value="">-- Choose Existing Gallery --</option>
                            <?php foreach ($galleryEvents as $evt): ?>
                                <option value="<?php echo $evt['id']; ?>"><?php echo htmlspecialchars($evt['title']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="new">+ Create New Event / Use Main Site Event</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div x-show="eventId === 'new'" x-transition class="space-y-3">
                    <label class="block text-xs font-bold text-blue-500 uppercase tracking-widest">Event Title (Type
                        freely or choose suggestion)</label>
                    <div class="relative">
                        <input type="text" x-model="newTitle" list="main-event-suggestions"
                            placeholder="e.g. Annual Summit 2026"
                            class="w-full bg-blue-50 border border-blue-100 text-blue-900 text-lg font-bold rounded-xl px-4 py-4 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <div class="mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z" />
                            </svg>
                            <span class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Tip: Choosing a
                                title from the list syncs it with main site events</span>
                        </div>
                    </div>
                    <datalist id="main-event-suggestions">
                        <?php foreach ($eventSuggestions as $sugg): ?>
                            <option value="<?php echo htmlspecialchars($sugg); ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
            </div>

            <!-- Dropzone -->
            <div class="relative group cursor-pointer">
                <input type="file" multiple accept="image/png, image/jpeg, image/webp" @change="handleFiles"
                    class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">

                <div class="border-3 border-dashed border-gray-200 rounded-3xl p-16 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50/30"
                    :class="{'border-blue-500 bg-blue-50/30': isDragging || selectedFiles.length > 0}">

                    <div x-show="selectedFiles.length === 0">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 text-gray-400 rounded-full mb-6 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Drag & Drop Photos</h3>
                        <p class="text-gray-400 font-medium">JPG, PNG, WEBP (Max 8MB)</p>
                    </div>

                    <div x-show="selectedFiles.length > 0">
                        <h3 class="text-2xl font-black text-blue-600 mb-2"
                            x-text="selectedFiles.length + ' Photos Selected'"></h3>
                        <div class="flex flex-wrap gap-2 justify-center max-h-40 overflow-y-auto">
                            <template x-for="file in selectedFiles">
                                <span
                                    class="bg-white border border-gray-200 px-3 py-1 rounded-lg text-xs font-bold text-gray-500"
                                    x-text="file.name"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6">
                <button type="submit" :disabled="uploading || ((selectedFiles.length === 0) && !newTitle)"
                    class="w-full bg-[#001F54] hover:bg-[#003893] text-white font-black py-5 rounded-2xl shadow-lg transition-all text-lg uppercase tracking-widest flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!uploading">Start Upload</span>
                    <span x-show="uploading">Processing...</span>
                    <!-- Spinner -->
                    <svg x-show="uploading" class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Status -->
            <div x-show="message" x-text="message"
                :class="success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="p-4 rounded-xl text-center font-bold text-sm"></div>
        </form>
    </div>
</div>

<script>
    function galleryUploader() {
        return {
            eventId: '',
            newTitle: '',
            selectedFiles: [],
            uploading: false,
            message: '',
            success: false,
            isDragging: false,
            handleFiles(e) { this.selectedFiles = Array.from(e.target.files); },
            async submitUpload() {
                if (!this.eventId && !this.newTitle) return alert('Select or create an event');
                if (!this.selectedFiles.length && !this.newTitle) return alert('Select files or enter a new event name');

                this.uploading = true;
                this.message = '';
                const fd = new FormData();
                fd.append('csrf_token', '<?php echo generateCSRFToken(); ?>');
                if (this.eventId === 'new') fd.append('new_event_title', this.newTitle);
                else fd.append('event_id', this.eventId);

                this.selectedFiles.forEach(f => fd.append('images[]', f));

                try {
                    const res = await fetch('../api/admin/gallery-upload-handler.php', { method: 'POST', body: fd });
                    const data = await res.json();
                    this.success = data.success;
                    this.message = data.success ? 'Upload Successful!' : (data.messages?.join(', ') || data.message || 'Error');
                    if (data.success) setTimeout(() => window.location.href = 'gallery-list.php', 1500);
                } catch (e) {
                    this.message = 'Network Error';
                } finally {
                    this.uploading = false;
                }
            }
        }
    }
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>