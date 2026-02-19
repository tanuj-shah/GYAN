<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/events.php';

// Backup Real-time Check
updateEventStatuses();

$activeTab = isset($_GET['tab']) && $_GET['tab'] === 'past' ? 'past' : 'upcoming';
$events = getEvents(20, 0, $activeTab);

// Find the spotlight event (the nearest upcoming one)
$spotlight = null;
if ($activeTab === 'upcoming' && !empty($events)) {
    $spotlight = $events[0]; // Already ordered by date
    $otherEvents = array_slice($events, 1);
} else {
    $otherEvents = $events;
}

$pageTitle = "Events & Initiatives";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen">
    <!-- Premium Hero Section -->
    <div class="relative bg-white pt-24 pb-16 md:pb-32 overflow-hidden">
        <!-- Decorative Framing Flags -->
        <div class="absolute inset-0 pointer-events-none hidden md:block select-none overflow-hidden"
            aria-hidden="true">
            <img src="img/flag.webp" alt=""
                class="absolute left-[-2rem] lg:left-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-50 object-contain"
                loading="lazy">
            <img src="img/flag.webp" alt=""
                class="absolute right-[-2rem] lg:right-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-40 object-contain scale-x-[-1]"
                loading="lazy">
        </div>
        <div class="absolute inset-0 z-0">
            <!-- Mountain Backdrop -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="img/mountain.webp" alt=""
                    class="absolute inset-0 w-full h-[130%] object-cover object-top opacity-20 -translate-y-12">
            </div>
            <div class="absolute inset-0 bg-white opacity-20">
            </div>
            <div class="absolute inset-x-0 bottom-0 h-24"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1
                class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl uppercase tracking-widest">
                Our Initiatives</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Global Action Hub</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                Join our movement of change. From local community projects to global summits, explore how we are shaping
                the future together.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-12 md:pb-24">
        <!-- Sophisticated Tab Navigation -->
        <div class="mb-12 flex justify-center">
            <nav class="inline-flex bg-white/50 backdrop-blur-md p-1.5 rounded-2xl border border-gray-100 shadow-premium"
                aria-label="Tabs">
                <a href="?tab=upcoming"
                    class="<?php echo $activeTab === 'upcoming' ? 'bg-primary text-white shadow-glow' : 'text-gray-500 hover:text-primary'; ?> whitespace-nowrap py-3 px-8 rounded-xl font-bold text-sm transition-all duration-300">
                    Upcoming Initiatives
                </a>
                <a href="?tab=past"
                    class="<?php echo $activeTab === 'past' ? 'bg-primary text-white shadow-glow' : 'text-gray-500 hover:text-primary'; ?> whitespace-nowrap py-3 px-8 rounded-xl font-bold text-sm transition-all duration-300">
                    Past Achievements
                </a>
            </nav>
        </div>

        <?php if ($spotlight): ?>
            <!-- Spotlight Event with Countdown -->
            <div class="mb-20" data-aos="zoom-in" data-aos-duration="1000">
                <div
                    class="bg-white rounded-[2.5rem] overflow-hidden shadow-premium border-[3px] border-[#8B0000] flex flex-col lg:flex-row">
                    <!-- Image Area -->
                    <div class="lg:w-1/2 relative h-96 lg:h-auto">
                        <?php if (!empty($spotlight['image_url'])): ?>
                            <img class="absolute inset-0 w-full h-full object-cover"
                                src="<?php echo htmlspecialchars(ltrim($spotlight['image_url'], '/')); ?>"
                                alt="<?php echo htmlspecialchars($spotlight['title']); ?>">
                        <?php else: ?>
                            <div class="absolute inset-0 bg-gray-100 flex items-center justify-center text-gray-300">
                                <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="absolute top-6 left-6">
                            <span
                                class="px-4 py-2 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-glow">
                                Next Major Initiative
                            </span>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="lg:w-1/2 p-6 md:p-14 flex flex-col">
                        <div class="flex items-center text-primary font-bold text-sm tracking-widest uppercase mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?php echo date('l, F j, Y', strtotime($spotlight['event_date'])); ?>
                        </div>

                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 leading-tight">
                            <?php echo htmlspecialchars($spotlight['title']); ?>
                        </h2>

                        <!-- Real-time Countdown -->
                        <div class="grid grid-cols-4 gap-4 mb-8 text-center" id="countdown"
                            data-date="<?php echo $spotlight['event_date']; ?>">
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <span class="block text-2xl font-black text-primary" id="days">00</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Days</span>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <span class="block text-2xl font-black text-primary" id="hours">00</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Hours</span>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <span class="block text-2xl font-black text-primary" id="minutes">00</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Mins</span>
                            </div>
                            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                                <span class="block text-2xl font-black text-primary" id="seconds">00</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Secs</span>
                            </div>
                        </div>

                        <p class="text-gray-500 mb-10 text-lg leading-relaxed line-clamp-3 italic">
                            "<?php echo htmlspecialchars($spotlight['description']); ?>"
                        </p>

                        <div class="mt-auto flex flex-col sm:flex-row gap-4">
                            <a href="event.php?slug=<?php echo htmlspecialchars($spotlight['slug']); ?>"
                                class="flex-1 text-center py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition-all shadow-lg transform active:scale-95">
                                Explore Details
                            </a>
                            <?php if ($spotlight['registration_enabled']): ?>
                                <a href="<?php echo htmlspecialchars($spotlight['registration_url']); ?>" target="_blank"
                                    class="flex-1 text-center py-4 bg-primary text-white font-bold rounded-2xl shadow-glow hover:bg-primary-dark transition-all transform active:scale-95 flex items-center justify-center">
                                    Register Now
                                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Event Grid -->
        <?php if (!empty($otherEvents)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php foreach ($otherEvents as $index => $event): ?>
                    <article
                        class="bg-white rounded-[2rem] shadow-sm border-[3px] border-[#8B0000] overflow-hidden group hover:shadow-2xl transition-all duration-500 flex flex-col"
                        data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">

                        <!-- Image Container -->
                        <div class="relative h-48 overflow-hidden">
                            <?php if (!empty($event['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars(ltrim($event['image_url'], '/')); ?>"
                                    alt="<?php echo htmlspecialchars($event['title']); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-white border border-gray-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-4 right-4">
                                <div class="bg-white/95 backdrop-blur-md rounded-2xl p-2 px-3 text-center shadow-sm">
                                    <span
                                        class="block text-lg font-black text-primary leading-none"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="py-3 px-6 flex-1 flex flex-col">
                            <div class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
                                <svg class="w-4 h-4 mr-2 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <?php echo htmlspecialchars($event['location'] ?: 'Global Event'); ?>
                            </div>

                            <h3
                                class="text-xl font-extrabold text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                <a href="event.php?slug=<?php echo htmlspecialchars($event['slug']); ?>">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </a>
                            </h3>

                            <p class="text-gray-500 mb-2 line-clamp-3 text-sm leading-relaxed">
                                <?php echo htmlspecialchars(mb_strimwidth($event['description'], 0, 150, "...")); ?>
                            </p>

                            <!-- Mini Registration Info -->
                            <?php if ($activeTab === 'upcoming' && $event['registration_enabled'] && $event['registration_deadline']): ?>
                                <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Reg.
                                        Deadline</span>
                                    <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">
                                        <?php echo date('M j, Y', strtotime($event['registration_deadline'])); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="mt-2">
                                <a href="event.php?slug=<?php echo htmlspecialchars($event['slug']); ?>"
                                    class="inline-flex items-center text-primary font-black text-sm group/link">
                                    Initiative Details
                                    <svg class="w-4 h-4 ml-2 transform group-hover/link:translate-x-2 transition-all"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php elseif (empty($spotlight)): ?>
            <!-- Improved Empty State -->
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 py-24 text-center" data-aos="fade-up">
                <div
                    class="w-24 h-24 bg-white border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-4">No initiatives found.</h2>
                <p class="text-gray-500 max-w-sm mx-auto leading-relaxed italic">Stay tuned! We're currently
                    curating
                    powerful new programs for our global community.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize AOS
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                once: true,
                easing: 'ease-out-quad'
            });
        }

        // Countdown Timer Logic
        const countdownEl = document.getElementById('countdown');
        if (countdownEl) {
            const eventDate = new Date(countdownEl.getAttribute('data-date')).getTime();

            const timer = setInterval(function () {
                const now = new Date().getTime();
                const distance = eventDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "<div class='col-span-4 py-4 text-primary font-bold uppercase tracking-widest'>The initiative has commenced!</div>";
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').innerText = d.toString().padStart(2, '0');
                document.getElementById('hours').innerText = h.toString().padStart(2, '0');
                document.getElementById('minutes').innerText = m.toString().padStart(2, '0');
                document.getElementById('seconds').innerText = s.toString().padStart(2, '0');
            }, 1000);
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>