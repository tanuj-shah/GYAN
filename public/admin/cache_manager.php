<?php
/**
 * Cache Manager - Simple admin interface for clearing cache
 */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/cache_helper.php';

// Check if user is admin
requireAdmin();

$message = '';
$stats = getCacheStats();

// Handle cache clear action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'clear_all') {
        $deleted = clearCache();
        $message = "✓ Successfully cleared {$deleted} cached page(s)";
        $stats = getCacheStats(); // Refresh stats
    }
}

$pageTitle = "Cache Manager";
require_once __DIR__ . '/../../includes/admin.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Cache Manager</h1>

    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Cache Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Cache Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded">
                <div class="text-gray-600 text-sm">Cached Pages</div>
                <div class="text-3xl font-bold text-primary">
                    <?php echo $stats['files']; ?>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded">
                <div class="text-gray-600 text-sm">Total Size</div>
                <div class="text-3xl font-bold text-primary">
                    <?php echo $stats['size_mb']; ?> MB
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded">
                <div class="text-gray-600 text-sm">Status</div>
                <div class="text-xl font-bold text-green-600">
                    <?php echo $stats['files'] > 0 ? 'Active' : 'Empty'; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Actions</h2>
        <form method="POST" onsubmit="return confirm('Are you sure you want to clear all cached pages?');">
            <input type="hidden" name="action" value="clear_all">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded">
                Clear All Cache
            </button>
            <p class="text-gray-600 text-sm mt-2">
                This will delete all cached pages. They will be regenerated on next visit.
            </p>
        </form>
    </div>

    <!-- Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <h3 class="font-semibold text-blue-900 mb-2">About Page Caching</h3>
        <p class="text-blue-800 text-sm">
            Page caching stores fully rendered HTML pages to disk, dramatically reducing database queries and PHP
            execution time.
            Cached pages are automatically regenerated when they expire or when you clear the cache.
        </p>
        <p class="text-blue-800 text-sm mt-2">
            <strong>When to clear cache:</strong> After making significant content updates, changing layouts, or
            modifying CSS/JS files.
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>