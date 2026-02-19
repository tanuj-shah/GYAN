<?php
// public/admin/index.php
// Admin Dashboard
require_once __DIR__ . '/header.php';

// Stats are fetched in getDashboardStats() from includes/admin.php (included by header.php)
$stats = getDashboardStats();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Health Check</h1>
    <p class="mt-2 text-gray-600">Overview of platform activity and quick management.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <!-- Total Members -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-card transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="p-2 rounded-lg bg-white border border-gray-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <span
                class="text-xs font-bold text-blue-600 bg-white border border-blue-100 px-2 py-1 rounded-full uppercase tracking-wider">Community</span>
        </div>
        <h2 class="text-3xl font-bold text-gray-900"><?php echo $stats['users']; ?></h2>
        <p class="text-sm text-gray-500 mt-1">Total registered users</p>
    </div>

    <!-- Active Events -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-card transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="p-2 rounded-lg bg-white border border-orange-100 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span
                class="text-xs font-bold text-orange-600 bg-white border border-orange-100 px-2 py-1 rounded-full uppercase tracking-wider">Events</span>
        </div>
        <h2 class="text-3xl font-bold text-gray-900"><?php echo $stats['events']; ?></h2>
        <p class="text-sm text-gray-500 mt-1">Total events created</p>
    </div>

    <!-- Pending Blogs -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-card transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="p-2 rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                </svg>
            </div>
            <?php if ($stats['pending_blogs'] > 0): ?>
                <span class="animate-pulse h-2 w-2 rounded-full bg-primary absolute -mt-1 -mr-1"></span>
            <?php endif; ?>
            <span
                class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-full uppercase tracking-wider">Pending
                Posts</span>
        </div>
        <h2 class="text-3xl font-bold text-gray-900"><?php echo $stats['pending_blogs']; ?></h2>
        <p class="text-sm text-gray-500 mt-1">Blogs awaiting review</p>
    </div>

    <!-- New Messages -->
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-card transition-all group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="p-2 rounded-lg bg-white border border-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <span
                class="text-xs font-bold text-green-600 bg-white border border-green-100 px-2 py-1 rounded-full uppercase tracking-wider">Inbox</span>
        </div>
        <h2 class="text-3xl font-bold text-gray-900"><?php echo $stats['messages']; ?></h2>
        <p class="text-sm text-gray-500 mt-1">Unread messages</p>
    </div>
</div>

<!-- Grid for Tasks and Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Actions -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Recent Platform Activity</h3>
                <button class="text-sm text-primary font-medium hover:underline">View All</button>
            </div>
            <div class="p-6">
                <!-- Placeholder for activity feed or something similar -->
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div
                            class="h-10 w-10 rounded-full bg-white border border-gray-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-gray-500">Log</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900 font-medium">System setup complete</p>
                            <p class="text-xs text-gray-500 mt-1">Administrative environment initialized successfully.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tooltip -->
    <div class="space-y-6">
        <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-glow">
            <h3 class="font-bold mb-4">Quick Management</h3>
            <div class="space-y-3">
                <a href="blogs.php"
                    class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/10 group">
                    <span class="text-sm font-medium">Review Blog Posts</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="users.php"
                    class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/10 group">
                    <span class="text-sm font-medium">Community Directory</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="events.php"
                    class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/10 group">
                    <span class="text-sm font-medium">Event Calendar</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</body>

</html>