<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to access this page.');
    redirect('login.php');
}

$pageTitle = "Command Center";
generateCSRFToken(); // Ensure CSRF token is ready for notification interactions
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/vision2035.php';

$user = getProfile($_SESSION['user_id']);

// Logic for Dynamic Greeting
$hour = date('H');
$greeting = "Good Day";
if ($hour < 12)
    $greeting = "Good Morning";
elseif ($hour < 18)
    $greeting = "Good Afternoon";
else
    $greeting = "Good Evening";

// Mock Stats for High-Class UI Depth (Can be dynamically tied to DB later)
$profileStrength = 0;
if (!empty($user['photo_url']))
    $profileStrength += 20;
if (!empty($user['bio']))
    $profileStrength += 20;
if (!empty($user['profession']))
    $profileStrength += 20;
if (!empty($user['country']))
    $profileStrength += 20;
if (!empty($user['skills']))
    $profileStrength += 10;

$socialLinks = json_decode($user['social_links'] ?? '{}', true);
if (!empty($socialLinks) && (isset($socialLinks['facebook']) || isset($socialLinks['linkedin']) || isset($socialLinks['twitter'])))
    $profileStrength += 10;

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$blogCount = $stmt->fetchColumn();

// Fetch Proposals
$userProposals = getUserProposals($_SESSION['user_id']);

// Fetch Milestones for Global Journey
$milestones = [];

// 1. Alliance Entry (Join Date)
if (!empty($user['created_at'])) {
    $milestones[] = [
        'title' => 'Alliance Entry',
        'date' => $user['created_at'],
        'icon' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" /></svg>',
        'desc' => 'Joined the elite network of Nepali youth.'
    ];
}

// 2. First Insight (Earliest Blog)
$stmt = $pdo->prepare("SELECT MIN(created_at) FROM blogs WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$_SESSION['user_id']]);
$firstBlogDate = $stmt->fetchColumn();
if ($firstBlogDate) {
    $milestones[] = [
        'title' => 'First Insight',
        'date' => $firstBlogDate,
        'icon' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" /><path d="M14 2v4a2 2 0 002 2h4" /></svg>',
        'desc' => 'Published your first professional story.'
    ];
}

// 3. Visionary Milestone (Earliest Proposal)
$stmt = $pdo->prepare("SELECT MIN(submitted_at) FROM vision_proposals WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$firstProposalDate = $stmt->fetchColumn();
if ($firstProposalDate) {
    $milestones[] = [
        'title' => 'Visionary Pulse',
        'date' => $firstProposalDate,
        'icon' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" /></svg>',
        'desc' => 'Contributed your first vision for Nepal.'
    ];
}

// 4. Profile Elite (If 100%)
if ($profileStrength >= 100) {
    $milestones[] = [
        'title' => 'Profile Elite',
        'date' => date('Y-m-d H:i:s'), // Current time as it's active now
        'icon' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" /></svg>',
        'desc' => 'Achieved professional profile excellence.'
    ];
}

// Sort milestones by date descending
usort($milestones, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>

<div class="min-h-screen pt-24 pb-20">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="lg:flex lg:gap-8">
            <!-- Sidebar: Elite Navigation Column -->
            <aside class="w-full lg:w-72 flex-shrink-0 mb-8 lg:mb-0" data-aos="fade-right">
                <div
                    class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-premium border border-white/20 p-6 md:p-8 static lg:sticky top-32">
                    <div class="text-center mb-10">
                        <div class="relative inline-block mb-4">
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg mx-auto"
                                src="<?php echo !empty($user['photo_url']) ? htmlspecialchars(ltrim($user['photo_url'], '/')) : 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name'] ?? 'User') . '&background=random'; ?>"
                                alt="">
                            <div
                                class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full">
                            </div>
                        </div>
                        <h3 class="font-black text-gray-900 text-lg leading-tight">
                            <?php echo htmlspecialchars($user['full_name'] ?? 'Member'); ?>
                        </h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                            <?php echo htmlspecialchars($user['profession'] ?: 'GYAN Explorer'); ?>
                        </p>
                    </div>

                    <nav class="space-y-2">
                        <a href="dashboard.php"
                            class="flex items-center space-x-4 px-6 py-4 rounded-2xl bg-primary/5 text-primary font-black text-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Overview</span>
                        </a>
                        <a href="edit_profile.php"
                            class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile Settings</span>
                        </a>
                        <a href="vision-2035.php"
                            class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Vision 2035</span>
                        </a>
                        <a href="blog-create.php"
                            class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Submit Content</span>
                        </a>
                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <a href="directory.php"
                                class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Community</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 mt-6 lg:mt-0">
                <!-- Dashboard Header -->
                <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6" data-aos="fade-down"
                    x-data="{ 
                        notifications: [], 
                        unreadCount: 0,
                        loading: true,
                        async fetchNotifications() {
                            try {
                                const response = await fetch('api/vision2035/get_notifications.php');
                                const data = await response.json();
                                if (data.success) {
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unread_count;
                                }
                            } catch (err) {
                                console.error('Failed to fetch notifications:', err);
                            } finally {
                                this.loading = false;
                            }
                        },
                        async markRead(id) {
                            const formData = new FormData();
                            formData.append('notification_id', id);
                            formData.append('csrf_token', '<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>');
                            
                            try {
                                const response = await fetch('api/vision2035/mark_read.php', {
                                    method: 'POST',
                                    body: formData
                                });
                                const data = await response.json();
                                if (data.success) {
                                    this.notifications = this.notifications.filter(n => n.id != id);
                                    if (this.unreadCount > 0) this.unreadCount--;
                                }
                            } catch (err) {
                                console.error('Failed to mark read:', err);
                            }
                        }
                    }" x-init="fetchNotifications()">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">
                            <?php echo $greeting; ?>,
                            <?php echo explode(' ', htmlspecialchars($user['full_name'] ?? 'Member'))[0]; ?>
                        </h1>
                        <p class="text-gray-500 font-medium">Your global alliance control center is ready for impact.
                        </p>
                    </div>

                    <!-- Notification Center -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-3 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                            <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-2 right-2 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                                </span>
                            </template>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-4 w-80 bg-white rounded-[2rem] shadow-premium border border-gray-100 z-50 overflow-hidden"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-cloak>
                            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                                <h4 class="font-black text-gray-900 text-sm uppercase tracking-widest">Alerts</h4>
                                <span x-text="unreadCount + ' New'"
                                    class="text-[10px] font-black text-primary uppercase"></span>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-8 text-center">
                                        <p class="text-sm text-gray-400 italic">No new notifications</p>
                                    </div>
                                </template>
                                <template x-for="n in notifications" :key="n.id">
                                    <div
                                        class="p-6 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 relative group">
                                        <div class="flex items-start gap-4">
                                            <div class="w-2 h-2 mt-1.5 rounded-full bg-primary shrink-0"></div>
                                            <div class="flex-1">
                                                <p x-text="n.message"
                                                    class="text-xs font-bold text-gray-800 leading-relaxed mb-2"></p>
                                                <div class="flex items-center justify-between">
                                                    <span x-text="new Date(n.created_at).toLocaleDateString()"
                                                        class="text-[10px] text-gray-400 font-bold uppercase"></span>
                                                    <button @click="markRead(n.id)"
                                                        class="text-[10px] font-black text-secondary hover:text-primary uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">
                                                        Dismiss
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insight Cards (Stats Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500"
                        data-aos="fade-up">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-xs font-black text-primary uppercase tracking-[0.2em]">Profile
                                Strength</span>
                            <span class="text-xl font-black text-gray-900"><?php echo $profileStrength; ?>%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary transition-all duration-1000"
                                style="width: <?php echo $profileStrength; ?>%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 italic">
                            <?php echo $profileStrength < 100 ? 'Complete your bio to reach 100%' : 'Your profile is elite.'; ?>
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500"
                        data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-xs font-black text-blue-600 uppercase tracking-[0.2em]">Insights
                                Shared</span>
                            <span class="text-xl font-black text-gray-900"><?php echo $blogCount; ?></span>
                        </div>
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-white border-2 border-white flex items-center justify-center text-blue-600 text-xs font-bold">
                                B</div>
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-blue-800 text-xs font-bold">
                                <?php echo $blogCount; ?>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 italic">Your contributions shape our knowledge hub.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500"
                        data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-xs font-black text-green-600 uppercase tracking-[0.2em]">Alliance
                                Status</span>
                            <span class="text-xl font-black text-gray-900 uppercase">Active</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-gray-500">Verified Professional</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 italic">Globally recognized credentials.</p>
                    </div>
                </div>

                <!-- Vision 2035 Promotional Card -->
                <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden mb-12"
                    data-aos="fade-up">
                    <div class="p-6 md:p-10 flex flex-col md:flex-row items-center gap-8">
                        <!-- Left: Logo Container -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-24 h-24 md:w-56 md:h-56 bg-white rounded-[2.5rem] p-4 md:p-8 flex items-center justify-center shadow-xl">
                                <img src="img/Vision_2035_logo.png" alt="Vision 2035"
                                    class="w-full h-full object-contain">
                            </div>
                        </div>

                        <!-- Right: Content -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-4">Vision 2035
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-6">
                                Shape Nepal's future by contributing innovative ideas and solutions. Use your expertise
                                to address national and local challenges. Your vision can drive meaningful change for
                                our communities.
                            </p>
                            <a href="vision-2035.php"
                                class="inline-block bg-primary hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest px-8 py-4 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Contribute Your Vision
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Vision 2035 Proposals Section -->
                <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden mb-12"
                    data-aos="fade-up">
                    <div class="p-6 md:p-10 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Your Vision 2035
                            Proposals</h3>
                        <div class="flex items-center gap-3">
                            <a href="vision-2035.php"
                                class="text-xs font-black text-gray-400 hover:text-primary uppercase tracking-widest transition-all">See
                                All</a>
                            <a href="vision-2035.php"
                                class="text-xs font-black text-secondary uppercase tracking-widest bg-white border border-blue-50 px-5 py-2.5 rounded-xl hover:bg-secondary hover:text-white transition-all">Submit
                                New</a>
                        </div>
                    </div>

                    <div class="p-6 md:p-10">
                        <?php
                        $displayProposals = array_slice($userProposals, 0, 5);
                        if (empty($displayProposals)):
                            ?>
                            <div class="text-center py-20">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">No proposals yet.</h4>
                                <p class="text-gray-400 text-sm max-w-xs mx-auto">Your innovative ideas can shape the future
                                    of Nepal. Submit your first proposal today.</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            <th class="pb-6">Proposal Detail</th>
                                            <th class="pb-6">Status</th>
                                            <th class="pb-6">Priority</th>
                                            <th class="pb-6 text-right">Submitted Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <?php foreach ($displayProposals as $proposal): ?>
                                            <tr class="group">
                                                <td class="py-6">
                                                    <div
                                                        class="font-black text-gray-900 group-hover:text-primary transition-colors">
                                                        <?php echo htmlspecialchars($proposal['title']); ?>
                                                    </div>
                                                    <div
                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                                        <?php echo htmlspecialchars($proposal['category']); ?>
                                                    </div>
                                                </td>
                                                <td class="py-6">
                                                    <?php
                                                    $statusClasses = [
                                                        'Submitted' => 'bg-white border border-blue-100 text-blue-600',
                                                        'Under review' => 'bg-white border border-purple-100 text-purple-600',
                                                        'Rejected' => 'bg-white border border-red-100 text-red-600',
                                                        'Check Your Mail' => 'bg-white border border-green-100 text-green-600'
                                                    ];
                                                    $sClass = $statusClasses[$proposal['status']] ?? 'bg-white text-gray-600';
                                                    ?>
                                                    <span
                                                        class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $sClass; ?>">
                                                        <?php echo $proposal['status']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-6">
                                                    <?php
                                                    $priorityClasses = [
                                                        'Low' => 'text-green-600',
                                                        'Medium' => 'text-yellow-600',
                                                        'High' => 'text-red-600'
                                                    ];
                                                    $pColor = $priorityClasses[$proposal['priority']] ?? 'text-gray-400';
                                                    ?>
                                                    <span
                                                        class="text-[10px] font-black uppercase tracking-widest <?php echo $pColor; ?>">
                                                        <?php echo $proposal['priority']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-6 text-right">
                                                    <span
                                                        class="text-xs font-bold text-gray-500 uppercase"><?php echo date('M d, Y', strtotime($proposal['submitted_at'])); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Editorial Content Management (Blogs) -->
                <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden mb-12"
                    data-aos="fade-up">
                    <div class="p-6 md:p-10 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Your Editorial Works
                        </h3>
                        <div class="flex items-center gap-3">
                            <a href="blog.php"
                                class="text-xs font-black text-gray-400 hover:text-primary uppercase tracking-widest transition-all">See
                                All</a>
                            <a href="blog-create.php"
                                class="text-xs font-black text-primary uppercase tracking-widest bg-white border border-red-100 px-5 py-2.5 rounded-xl hover:bg-primary hover:text-white transition-all">New
                                Story</a>
                        </div>
                    </div>

                    <div class="p-6 md:p-10">
                        <?php
                        // Already handled by fetch above
                        $stmt2 = $pdo->prepare("SELECT * FROM blogs WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                        $stmt2->execute([$_SESSION['user_id']]);
                        $blogList = $stmt2->fetchAll();

                        if (empty($blogList)):
                            ?>
                            <div class="text-center py-20">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 2v4a2 2 0 002 2h4" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">Pen your first masterpiece.</h4>
                                <p class="text-gray-400 text-sm max-w-xs mx-auto">Share your professional insights with the
                                    global community of Nepali youth.</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            <th class="pb-6">Story Title</th>
                                            <th class="pb-6">Current Status</th>
                                            <th class="pb-6 text-right">Published Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <?php foreach ($blogList as $blogItem): ?>
                                            <tr class="group">
                                                <td class="py-6">
                                                    <div
                                                        class="font-black text-gray-900 group-hover:text-primary transition-colors">
                                                        <?php echo htmlspecialchars($blogItem['title']); ?>
                                                    </div>
                                                    <div
                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                                        <?php echo htmlspecialchars($blogItem['slug']); ?>
                                                    </div>
                                                </td>
                                                <td class="py-6">
                                                    <?php
                                                    $statusClasses = [
                                                        'pending' => 'bg-white border border-yellow-100 text-yellow-600',
                                                        'approved' => 'bg-white border border-green-100 text-green-600',
                                                        'rejected' => 'bg-white border border-red-100 text-red-600'
                                                    ];
                                                    $class = $statusClasses[$blogItem['status']] ?? 'bg-white text-gray-600';
                                                    ?>
                                                    <span
                                                        class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $class; ?>">
                                                        <?php echo $blogItem['status']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-6 text-right">
                                                    <span
                                                        class="text-xs font-bold text-gray-500 uppercase"><?php echo date('M d, Y', strtotime($blogItem['created_at'])); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Premium Activity Timeline -->
                <div class="bg-white rounded-[2.5rem] p-6 md:p-10 shadow-premium border border-gray-100"
                    data-aos="fade-up">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-10">Global Journey</h3>
                    <div
                        class="space-y-12 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gray-100">

                        <!-- Timeline Item: Today -->
                        <div
                            class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-white text-primary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-colors duration-500 group-hover:bg-primary group-hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                </svg>
                            </div>
                            <div
                                class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white/50 p-6 rounded-3xl border border-gray-100 transition-all duration-500 hover:shadow-lg">
                                <div class="flex items-center justify-between space-x-2 mb-1">
                                    <div class="text-xs font-black text-gray-900 uppercase">Live Session</div>
                                    <time class="text-[10px] font-bold text-primary uppercase">Active Now</time>
                                </div>
                                <div class="text-sm text-gray-500 italic leading-relaxed">Systematic connection with the
                                    Global Alliance command center.</div>
                            </div>
                        </div>

                        <?php foreach ($milestones as $index => $ms): ?>
                            <!-- Timeline Item: <?php echo $ms['title']; ?> -->
                            <div
                                class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                                <div
                                    class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-white text-primary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                    <?php echo $ms['icon']; ?>
                                </div>
                                <div
                                    class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white/50 p-6 rounded-3xl border border-gray-100">
                                    <div class="flex items-center justify-between space-x-2 mb-1">
                                        <div class="text-xs font-black text-gray-900 uppercase"><?php echo $ms['title']; ?></div>
                                        <time
                                            class="text-[10px] font-bold text-gray-400 uppercase"><?php echo date('F Y', strtotime($ms['date'])); ?></time>
                                    </div>
                                    <div class="text-sm text-gray-500 leading-relaxed"><?php echo $ms['desc']; ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 1000, once: true, easing: 'ease-out-quint' });
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>