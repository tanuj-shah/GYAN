<?php
// public/admin/header.php
// A simplified header for admin panel
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/admin.php';

// Enforce Admin Check
if (!function_exists('isAdmin')) {
    // Fallback if not loaded
}
if (!isAdmin()) {
    header("Location: ../../public/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GYAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        secondary: '#64748B',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Global High-Class Typography Overrides */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Times New Roman', Times, serif !important;
            font-weight: bold !important;
        }
    </style>
</head>

<body class="bg-white font-sans leading-normal tracking-normal">

    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 p-4 fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center h-20 flex-shrink-0 px-8 border-b border-white/5">
                <img class="h-14 w-auto object-contain mr-4" src="../img/GYAN_Logo.png?v=<?php echo time(); ?>"
                    alt="GYAN Logo">
                <div class="flex flex-col">
                    <span class="font-bold text-xl tracking-tight text-gray-900">GYAN Admin</span>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <a href="../../public/index.php" target="_blank"
                    class="text-gray-600 hover:text-primary text-sm font-medium transition-colors">View Site</a>
                <a href="../../public/logout.php"
                    class="bg-gray-900 hover:bg-black text-white text-sm py-2 px-6 rounded-lg font-medium transition-all">Logout</a>
            </div>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row pt-24 md:pt-32 min-h-screen">
        <!-- Sidebar -->
        <div
            class="bg-white border-r border-gray-100 w-full md:w-64 flex-shrink-0 md:fixed md:h-full md:top-28 md:pt-4">
            <ul class="space-y-1 px-3">
                <?php
                $pdo = getDBConnection();
                $unreadCount = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();

                $menuItems = [
                    ['Dashboard', 'index.php', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['Users', 'users.php', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['Events', 'events.php', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],

                    ['Blogs', 'blogs.php', 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z M14 2v4a2 2 0 002 2h4'],
                    ['Vision 2035', 'vision-proposals.php', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['Gallery', 'gallery-list.php', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['Messages', 'messages.php', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', $unreadCount]
                ];

                foreach ($menuItems as $item):
                    $isActive = (basename($_SERVER['PHP_SELF']) == $item[1]);
                    $badge = isset($item[3]) && $item[3] > 0 ? $item[3] : 0;
                    ?>
                    <li>
                        <a href="<?php echo $item[1]; ?>"
                            class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all <?php echo $isActive ? 'bg-primary/10 text-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'; ?>">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 <?php echo $isActive ? 'text-primary' : 'text-gray-400'; ?>"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="<?php echo $item[2]; ?>" />
                                </svg>
                                <?php echo $item[0]; ?>
                            </div>
                            <?php if ($badge > 0): ?>
                                <span class="bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                    <?php echo $badge; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="w-full md:ml-64 p-4 md:p-8 bg-white">