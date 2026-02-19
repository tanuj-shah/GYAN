<?php
// includes/header.php
// CRITICAL: All PHP code MUST come BEFORE any HTML output

// Determine base path for links
$isInAdmin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$basePath = $isInAdmin ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo isset($pageTitle) ? $pageTitle . ' - GYAN' : 'GYAN - Global Youth Alliance for Nepal'; ?>
    </title>

    <!-- Performance: Preconnect to external domains -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for interactivity - DEFERRED -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- SwiperJS CSS - Only load on homepage -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php endif; ?>

    <!-- AOS Animation CSS - DISABLED FOR PERFORMANCE 
    AOS causes significant scroll lag even with optimizations.
    All data-aos attributes in HTML are now ignored.
    <?php
    $aosPages = ['index.php', 'events.php', 'blog.php', 'about.php'];
    if (in_array(basename($_SERVER['PHP_SELF']), $aosPages)):
        ?>
        <link rel="preload" href="https://unpkg.com/aos@2.3.1/dist/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"></noscript>
    <?php endif; ?>
    -->

    <!-- Google Fonts: Outfit & Inter - OPTIMIZED for non-blocking -->
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap">
    </noscript>

    <!-- Custom CSS - Version-based cache busting -->
    <link rel="stylesheet" href="<?php echo ($isInAdmin ? '../' : '') . 'css/style.css?v=1.2.0'; ?>">

    <!-- Performance CSS - Scroll optimizations -->
    <link rel="stylesheet" href="<?php echo ($isInAdmin ? '../' : '') . 'css/performance.css?v=1.0.0'; ?>">

    <style>
        /* CRITICAL: Inline Styles to bypass external loading issues */
        :root {
            /* Creamy / Paper Palette */
            --sky-start: #FFFCF5;
            /* Very Light Cream */
            --sky-mid: #FBF7EB;
            /* Warm Beige */
            --sky-end: #F5ECD6;
            /* Deep Cream */
        }

        .site-bg {
            background-image:
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.08'/%3E%3C/svg%3E"),
                linear-gradient(180deg, #FFFCF5 0%, #FBF7EB 60%, #F5ECD6 100%) !important;

            background-color: #FBF7EB !important;
            background-repeat: repeat, no-repeat !important;
            background-attachment: fixed !important;
            min-height: 100vh;
        }

        /* Force cursor visibility to solve the disappearing pointer issue - DESKTOP ONLY */
        @media (hover: hover) and (pointer: fine) {

            html,
            body,
            * {
                cursor: auto !important;
            }

            a,
            button,
            [role="button"],
            .cursor-pointer,
            .swiper-button-next,
            .swiper-button-prev,
            .swiper-pagination-bullet {
                cursor: pointer !important;
            }

            .cursor-grab {
                cursor: grab !important;
            }

            .cursor-grabbing {
                cursor: grabbing !important;
            }
        }

        /* Global High-Class Typography Overrides - DISABLED locally to allow Comic Sans elsewhere */
        /*
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Times New Roman', Times, serif !important;
            font-weight: bold !important;
        }
        */
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Times New Roman', 'Times', 'serif', 'sans-serif'],
                    },
                    screens: {
                        'xs': '475px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#DC143C', // Crimson Red (Nepal Flag)
                            dark: '#B01030',
                            light: '#FF4D6D',
                        },
                        secondary: {
                            DEFAULT: '#003893', // Deep Blue (Nepal Flag)
                            dark: '#002866',
                            light: '#1F57B3',
                        },
                        accent: {
                            DEFAULT: '#059669', // Emerald Green (Growth)
                            light: '#34D399',
                        },
                        neutral: {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            800: '#1F2937',
                            900: '#111827',
                        },
                        'p-sky': {
                            start: '#bee9e8',
                            mid: '#cae9ff',
                            end: '#62b6cb',
                        }
                    },
                    backgroundImage: {
                        'gyan-gradient': 'linear-gradient(180deg, #bee9e8 0%, #cae9ff 60%, #62b6cb 100%)',
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
                        'glow': '0 0 15px rgba(220, 20, 60, 0.3)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Smooth scrolling adjustments */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="site-bg text-gray-800 flex flex-col min-h-screen antialiased">

    <!-- 2-TIER HEADER -->
    <header class="w-full z-50 fixed top-0 transition-transform duration-300 font-sans" id="main-header"
        x-data="{ mobileMenuOpen: false }">

        <!-- TIER 1: Top Utility Bar -->
        <div id="top-bar"
            class="bg-secondary text-white h-[35px] border-b border-secondary-light/20 transition-all duration-300 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex justify-between items-center h-full text-xs font-bold uppercase tracking-wider">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Nepali Date: <span id="nepali-date" class="ml-1">Loading...</span>
                            <script>
                                    (function () {
                                        const months = ["Baisakh", "Jestha", "Ashad", "Shrawan", "Bhadra", "Ashwin", "Kartik", "Mangsir", "Poush", "Magh", "Falgun", "Chaitra"];

                                        const now = new Date();
                                        const formatter = new Intl.DateTimeFormat('en-US', {
                                            timeZone: 'Asia/Kathmandu',
                                            year: 'numeric',
                                            month: 'numeric',
                                            day: 'numeric'
                                        });

                                        const parts = formatter.formatToParts(now);
                                        const dateParts = {};
                                        parts.forEach(({ type, value }) => dateParts[type] = value);

                                        const year = parseInt(dateParts.year);
                                        const month = parseInt(dateParts.month); // 1-12
                                        const date = parseInt(dateParts.day);

                                        let bsYear, bsMonth, bsDay;

                                        // Precision fix for Feb 2026 (2082 BS)
                                        // Reference: Feb 1, 2026 = Magh 18, 2082 BS
                                        // Magh 2082 has 29 days.
                                        if (year === 2026 && month === 2) {
                                            bsYear = 2082;
                                            if (date <= 12) {
                                                bsMonth = 9; // Magh
                                                bsDay = date + 17; // 1 -> 18, 12 -> 29
                                            } else {
                                                bsMonth = 10; // Falgun
                                                bsDay = date - 12; // 13 -> 1, 14 -> 2
                                            }
                                        } else {
                                            // Fallback for other dates (approximate)
                                            bsYear = year + 56;
                                            if (month > 4 || (month === 4 && date >= 14)) bsYear++;
                                            bsMonth = (month + 8) % 12;
                                            bsDay = date + 17;
                                            if (bsDay > 30) {
                                                bsDay -= 30;
                                                bsMonth = (bsMonth + 1) % 12;
                                            }
                                        }

                                        const dateStr = months[bsMonth] + " " + bsDay + ", " + bsYear + " BS";
                                        document.getElementById('nepali-date').textContent = dateStr;
                                    })();
                            </script>
                        </span>
                        <span class="hidden md:flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Global Youth Hub
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="mailto:gyan@ird.com.np"
                            class="hover:text-primary-light transition-colors">gyan@ird.com.np</a>
                        <span class="hidden sm:inline">|</span>
                        <a href="tel:+977" class="hidden sm:inline hover:text-primary-light transition-colors">Connect
                            Globally</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- TIER 2: Main Navigation Bar -->
        <div id="main-nav"
            class="bg-white h-[60px] lg:h-[80px] border-b border-[#ECF0F1] shadow-[0_2px_8px_rgba(0,0,0,0.05)] transition-shadow duration-300 relative">
            <div class="w-full px-[30px] h-full">
                <div class="flex justify-between items-center h-full">

                    <!-- Left: Logo & Organization Info -->
                    <a href="<?php echo $basePath; ?>index.php" class="flex items-center group">
                        <img class="h-[40px] lg:h-[70px] w-auto object-contain"
                            src="<?php echo $basePath; ?>img/GYAN_Logo.png?v=<?php echo time(); ?>" alt="GYAN Logo">
                        <div class="ml-[15px] flex flex-col justify-center">
                            <span
                                class="font-serif font-bold text-[18px] lg:text-[24px] text-[#DC143C] leading-none tracking-tight group-hover:opacity-90 transition-opacity"
                                style="font-family: 'Times New Roman', Times, serif;">GYAN</span>
                            <span
                                class="font-serif font-bold text-[10px] lg:text-[13px] text-[#7F8C8D] leading-tight hidden sm:block"
                                style="font-family: 'Times New Roman', Times, serif;">Global
                                Youth Alliance for Nepal</span>
                        </div>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 text-gray-600 hover:text-primary focus:outline-none"
                        aria-label="Toggle Navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            x-show="!mobileMenuOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            x-show="mobileMenuOpen" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Center: Navigation Menu -->
                    <nav class="hidden lg:flex items-center space-x-[28px] justify-center flex-1 mx-4">
                        <?php
                        $navLinks = [
                            'Home' => 'index.php',
                            'About' => 'about.php',

                            'Events' => 'events.php',
                            'Community' => 'directory.php',
                            'Gallery' => 'gallery.php',
                            'Blog' => 'blog.php',
                            'Contact' => 'contact.php'
                        ];
                        foreach ($navLinks as $name => $link):
                            $isActive = (basename($_SERVER['PHP_SELF']) == $link);
                            ?>
                            <a href="<?php echo $basePath . $link; ?>" class="text-[15px] font-bold relative group py-2 transition-all duration-300
                                <?php echo $isActive ? 'text-[#4169E1]' : 'text-[#2C3E50] hover:text-[#4169E1]'; ?>"
                                style="font-family: 'Times New Roman', Times, serif;">
                                <?php echo $name; ?>
                                <span class="absolute bottom-0 left-0 h-[3px] bg-[#4169E1] transition-all duration-300
                                    <?php echo $isActive ? 'w-full' : 'w-0 group-hover:w-full'; ?>"></span>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <!-- Right: Action Buttons -->
                    <div class="hidden lg:flex items-center space-x-[12px]">

                        <?php if (isLoggedIn()): ?>
                            <a href="<?php echo $basePath; ?>dashboard.php"
                                class="inline-flex items-center text-[14px] font-bold text-[#2C3E50] hover:text-[#4169E1] transition-colors"
                                style="font-family: 'Times New Roman', Times, serif;">
                                <svg class="w-[18px] h-[18px] mr-[6px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="<?php echo $basePath; ?>api/auth/logout.php"
                                class="inline-flex items-center text-[14px] font-bold text-primary hover:text-primary-dark transition-colors"
                                style="font-family: 'Times New Roman', Times, serif;">
                                <svg class="w-[18px] h-[18px] mr-[6px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </a>
                        <?php else: ?>
                            <a href="<?php echo $basePath; ?>login.php"
                                class="inline-flex items-center text-[14px] font-bold text-[#2C3E50] hover:text-[#4169E1] transition-colors"
                                style="font-family: 'Times New Roman', Times, serif;">
                                <svg class="w-[18px] h-[18px] mr-[6px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Log in
                            </a>
                        <?php endif; ?>

                        <!-- Join GYAN -->
                        <?php if (!isLoggedIn()): ?>
                            <a href="<?php echo $basePath; ?>register.php"
                                class="bg-[#4169E1] hover:bg-[#2e55c6] text-white font-bold text-[14px] px-[24px] py-[10px] rounded-full transition-all duration-300 transform hover:-translate-y-[2px] hover:shadow-[0_4px_12px_rgba(65,105,225,0.3)]"
                                style="font-family: 'Times New Roman', Times, serif;">
                                Join GYAN
                            </a>
                        <?php endif; ?>

                        <!-- Donate -->
                        <a href="<?php echo $basePath; ?>donate.php"
                            class="bg-[#d62828] text-white hover:bg-[#b52222] font-bold text-[14px] px-[20px] py-[8px] rounded-full transition-all duration-300 flex items-center"
                            style="font-family: 'Times New Roman', Times, serif;">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Support
                        </a>
                    </div>




                    <!-- Mobile Navigation Drawer -->
                    <div class="fixed inset-0 z-[60] lg:hidden" x-show="mobileMenuOpen"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

                        <!-- Drawer -->
                        <nav class="fixed top-0 right-0 bottom-0 w-[80%] max-w-sm bg-white shadow-2xl flex flex-col h-full overflow-y-auto"
                            x-transition:enter="transition ease-in-out duration-300 transform"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in-out duration-300 transform"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <span class="font-heading font-bold text-xl text-primary">Menu</span>
                                <button @click="mobileMenuOpen = false"
                                    class="text-gray-500 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex-1 px-6 py-4 space-y-4 overflow-y-auto">
                                <?php foreach ($navLinks as $name => $link):
                                    $isActive = (basename($_SERVER['PHP_SELF']) == $link);
                                    ?>
                                    <a href="<?php echo $basePath . $link; ?>"
                                        class="block text-lg font-bold py-3 border-b border-gray-100 transition-colors <?php echo $isActive ? 'text-primary' : 'text-gray-800'; ?>">
                                        <?php echo $name; ?>
                                    </a>
                                <?php endforeach; ?>

                                <!-- Mobile Action Buttons -->
                                <div class="pt-6 space-y-4">
                                    <?php if (isLoggedIn()): ?>
                                        <a href="<?php echo $basePath; ?>dashboard.php"
                                            class="flex items-center text-lg font-bold text-gray-800 hover:text-primary transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Dashboard
                                        </a>
                                        <a href="<?php echo $basePath; ?>api/auth/logout.php"
                                            class="flex items-center text-lg font-bold text-red-600 hover:text-red-700 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo $basePath; ?>login.php"
                                            class="flex items-center text-lg font-bold text-gray-800 hover:text-primary transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Log in
                                        </a>
                                        <a href="<?php echo $basePath; ?>register.php"
                                            class="block w-full text-center bg-primary hover:bg-primary-dark text-white font-bold text-lg px-6 py-3 rounded-xl transition-all shadow-lg">
                                            Join GYAN
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo $basePath; ?>donate.php"
                                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold text-lg px-6 py-3 rounded-xl transition-all shadow-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Support Us
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 bg-gray-50 space-y-4 border-t border-gray-200">
                                <?php if (isLoggedIn()): ?>
                                    <a href="<?php echo $basePath; ?>dashboard.php"
                                        class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-gray-300 font-bold text-gray-700 hover:bg-gray-100 transition-colors">
                                        Dashboard
                                    </a>
                                    <a href="<?php echo $basePath; ?>api/auth/logout.php"
                                        class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors">
                                        Logout
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $basePath; ?>login.php"
                                        class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-gray-300 font-bold text-gray-700 hover:bg-gray-100 transition-colors">
                                        Log in
                                    </a>
                                    <a href="<?php echo $basePath; ?>register.php"
                                        class="flex items-center justify-center w-full py-3 px-4 rounded-xl bg-primary text-white font-bold shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors">
                                        Join GYAN
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo $basePath; ?>donate.php"
                                    class="flex items-center justify-center w-full py-3 px-4 rounded-xl bg-[#d62828] text-white font-bold hover:bg-[#b52222] transition-colors">
                                    Support Us
                                </a>
                            </div>
                        </nav>
                    </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-[115px]"></div>


    <!-- Main Content Wrapper -->
    <main class="flex-grow">
        <!-- NOTE: Removed pt-16 because new hero sections will likely be full width/height. Specific pages might need padding adjustments. -->

        <!-- Flash Messages -->
        <?php if ($success = getFlashMessage('success')): ?>
            <div class="fixed top-24 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-card border-l-4 border-green-500 overflow-hidden animate-slide-up"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">
                            <?php echo $success; ?>
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($error = getFlashMessage('error')): ?>
            <div class="fixed top-24 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-card border-l-4 border-red-500 overflow-hidden animate-slide-up"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">
                            <?php echo $error; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>