<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/events.php';

$slug = $_GET['slug'] ?? '';
$event = getEventBySlug($slug);

if (!$event) {
    redirect('events.php');
}

// Check status on load just in case
if ($event['event_status'] === 'upcoming' && $event['registration_deadline'] && strtotime($event['registration_deadline']) < time()) {
    $event['event_status'] = 'past';
    // Optionally update DB here primarily if not redundant
}

$gallery = getEventGallery($event['id']);
$pageTitle = $event['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen pb-12">

    <!-- Hero Header -->
    <div class="relative bg-gray-900 h-64 md:h-80">
        <?php if (!empty($event['image_url'])): ?>
            <img class="w-full h-full object-cover opacity-40"
                src="<?php echo htmlspecialchars(ltrim($event['image_url'], '/')); ?>"
                alt="<?php echo htmlspecialchars($event['title']); ?>">
        <?php else: ?>
            <div class="w-full h-full bg-gray-800 opacity-50"></div>
        <?php endif; ?>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center px-4 max-w-4xl">
                <span class="inline-block py-1 px-3 rounded-full bg-crimson text-white text-sm font-semibold mb-4">
                    <?php echo $event['event_status'] === 'upcoming' ? 'Upcoming Event' : 'Past Event'; ?>
                </span>
                <h1
                    class="text-2xl md:text-5xl font-extrabold text-white tracking-tight mb-4 font-montserrat leading-tight">
                    <?php echo htmlspecialchars($event['title']); ?>
                </h1>
                <div
                    class="flex flex-wrap items-center justify-center text-gray-200 gap-4 md:gap-8 text-sm md:text-base">
                    <span class="flex items-center">
                        <svg class="h-5 w-5 mr-2 text-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?php echo date('F j, Y, g:i A', strtotime($event['event_date'])); ?>
                    </span>
                    <?php if (!empty($event['location'])): ?>
                        <span class="flex items-center">
                            <svg class="h-5 w-5 mr-2 text-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Description & Gallery -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-lg shadow-xl p-6 md:p-8" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Event Details</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Posted <?php echo date('F j, Y', strtotime($event['posted_date'])); ?>
                    </div>
                </div>

                <!-- Gallery Section -->
                <?php if (count($gallery) > 0): ?>
                    <div class="bg-white rounded-lg shadow-xl p-8" data-aos="fade-up">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 border-l-4 border-crimson pl-4">Event Gallery</h2>
                        <div class="columns-1 md:columns-2 gap-4 space-y-4">
                            <?php foreach ($gallery as $img): ?>
                                <div
                                    class="break-inside-avoid shadow rounded-lg overflow-hidden bg-white border border-gray-100">
                                    <img class="w-full hover:opacity-90 transition"
                                        src="<?php echo htmlspecialchars(ltrim($img['image_url'], '/')); ?>"
                                        alt="<?php echo htmlspecialchars($img['title'] ?? 'Gallery Image'); ?>" loading="lazy">
                                    <?php if (!empty($img['title'])): ?>
                                        <div class="p-2 bg-white">
                                            <p class="text-xs text-center text-gray-600 font-medium">
                                                <?php echo htmlspecialchars($img['title']); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Premium Registration Card -->
                <?php if ($event['event_status'] === 'upcoming' && $event['registration_enabled']): ?>
                    <div class="sticky top-[130px] z-30" data-aos="fade-left">
                        <div
                            class="bg-white rounded-[2rem] shadow-premium overflow-hidden border border-gray-100 flex flex-col">
                            <!-- Premium Header for Card -->
                            <div class="bg-gray-900 px-6 py-4 md:px-8 md:py-6 relative overflow-hidden">
                                <div class="absolute inset-0 bg-gray-900 opacity-20">
                                </div>
                                <h3
                                    class="text-xl font-black text-white relative z-10 uppercase tracking-widest text-center">
                                    Secure Your Seat
                                </h3>
                                <p
                                    class="text-white/60 text-[10px] text-center uppercase tracking-[0.2em] mt-1 relative z-10 font-bold">
                                    Limited Capacity Initiative
                                </p>
                            </div>

                            <div class="p-6 md:p-8">
                                <?php if ($event['registration_deadline']): ?>
                                    <div x-data="countdown('<?php echo str_replace(' ', 'T', $event['registration_deadline']); ?>')"
                                        x-init="startTimer()" class="mb-8">
                                        <template x-if="!expired">
                                            <div>
                                                <div class="flex items-center justify-center space-x-2 mb-6">
                                                    <div class="h-px w-8 bg-gray-200"></div>
                                                    <span
                                                        class="text-[10px] font-black text-primary uppercase tracking-widest">Enrollment
                                                        Window</span>
                                                    <div class="h-px w-8 bg-gray-200"></div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-3 text-center mb-6">
                                                    <div class="bg-white rounded-2xl p-3 border border-gray-100">
                                                        <div class="text-xl font-black text-gray-900 leading-none"
                                                            x-text="days">0</div>
                                                        <div
                                                            class="text-[8px] text-gray-400 uppercase font-black tracking-tighter mt-1">
                                                            Days</div>
                                                    </div>
                                                    <div class="bg-white rounded-2xl p-3 border border-gray-100">
                                                        <div class="text-xl font-black text-gray-900 leading-none"
                                                            x-text="hours">0</div>
                                                        <div
                                                            class="text-[8px] text-gray-400 uppercase font-black tracking-tighter mt-1">
                                                            Hours</div>
                                                    </div>
                                                    <div class="bg-white rounded-2xl p-3 border border-gray-100">
                                                        <div class="text-xl font-black text-gray-900 leading-none"
                                                            x-text="minutes">0</div>
                                                        <div
                                                            class="text-[8px] text-gray-400 uppercase font-black tracking-tighter mt-1">
                                                            Mins</div>
                                                    </div>
                                                </div>

                                                <!-- Elite Progress Bar -->
                                                <?php
                                                $start = strtotime($event['posted_date']);
                                                $end = strtotime($event['registration_deadline']);
                                                $now = time();
                                                $total = $end - $start;
                                                $elapsed = $now - $start;
                                                $percent = ($total > 0) ? min(100, max(0, ($elapsed / $total) * 100)) : 100;
                                                ?>
                                                <div class="relative pt-1">
                                                    <div class="overflow-hidden h-1.5 mb-2 text-xs flex rounded bg-gray-100">
                                                        <div style="width:<?php echo $percent; ?>%"
                                                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary transition-all duration-1000">
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-between items-center px-1">
                                                        <span class="text-[9px] font-bold text-gray-400 uppercase">Registration
                                                            Status</span>
                                                        <span
                                                            class="text-[9px] font-black text-primary uppercase"><?php echo round($percent); ?>%
                                                            Elapsed</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="expired">
                                            <div
                                                class="p-6 bg-white rounded-2xl border border-dashed border-gray-200 text-gray-400 text-center font-bold text-sm uppercase tracking-widest">
                                                Submissions Closed
                                            </div>
                                        </template>

                                        <div x-show="!expired" class="mt-8">
                                            <a href="<?php echo htmlspecialchars($event['registration_url']); ?>"
                                                target="_blank"
                                                class="group relative block w-full text-center bg-primary text-white font-black py-5 px-6 rounded-2xl shadow-glow hover:bg-black transition-all transform active:scale-95 btn-shine-container overflow-hidden">
                                                <span
                                                    class="relative z-10 flex items-center justify-center uppercase tracking-widest text-sm">
                                                    Begin Registration
                                                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <p
                                                class="text-center text-[9px] text-gray-400 mt-4 uppercase font-bold tracking-[0.1em]">
                                                Verified portal &bull; external link
                                            </p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- No Deadline Registration -->
                                    <div class="mt-2">
                                        <a href="<?php echo htmlspecialchars($event['registration_url']); ?>" target="_blank"
                                            class="group block w-full text-center bg-primary text-white font-black py-5 px-6 rounded-2xl shadow-glow hover:bg-black transition-all transform active:scale-95 uppercase tracking-widest text-sm">
                                            Apply Today
                                        </a>
                                        <p
                                            class="text-center text-[10px] text-gray-400 mt-4 uppercase font-bold tracking-widest">
                                            Enrollment currently open</p>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-8 pt-6 border-t border-gray-50 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Deadline</span>
                                        <span class="text-xs font-black text-gray-900 uppercase">
                                            <?php echo $event['registration_deadline'] ? date('M d, Y', strtotime($event['registration_deadline'])) : 'Open'; ?>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Location</span>
                                        <span class="text-xs font-black text-primary uppercase text-right max-w-[60%]">
                                            <?php echo htmlspecialchars($event['location'] ?? 'Global'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($event['event_status'] === 'past'): ?>
                    <div class="bg-white rounded-xl p-6 border border-gray-200 text-center">
                        <span class="inline-block p-3 rounded-full bg-white border border-gray-200 text-gray-500 mb-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold text-gray-700">Event Concluded</h3>
                        <p class="text-sm text-gray-500 mt-1">Registration is closed for this event.</p>
                    </div>
                <?php endif; ?>

                <!-- Elite Share Card -->
                <div class="bg-white rounded-[2rem] shadow-premium p-8 border border-gray-100" data-aos="fade-left"
                    data-aos-delay="100">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 text-center">
                        Circulate Initiative</h4>
                    <div class="flex flex-col space-y-3">
                        <button
                            onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank')"
                            class="flex items-center justify-center w-full bg-white text-gray-900 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all duration-300 border border-gray-100">
                            <span class="mr-2">&bull;</span> Facebook
                        </button>
                        <button
                            onclick="window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('Check out this event: <?php echo addslashes($event['title']); ?>'), '_blank')"
                            class="flex items-center justify-center w-full bg-white text-gray-900 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all duration-300 border border-gray-100">
                            <span class="mr-2">&bull;</span> Twitter
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="text-center mt-12 mb-8">
            <a href="events.php"
                class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                &larr; Back to Initiatives List
            </a>
        </div>
    </div>
</div>

<script>
    function countdown(deadlineDate) {
        return {
            expiry: new Date(deadlineDate).getTime(),
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            expired: false,
            startTimer() {
                this.update();
                setInterval(() => { this.update(); }, 1000);
            },
            update() {
                const now = new Date().getTime();
                const distance = this.expiry - now;

                if (distance < 0) {
                    this.expired = true;
                    this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                } else {
                    this.expired = false;
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                }
            }
        }
    }
</script>

<style>
    .shadow-premium {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
    }

    .text-crimson {
        color: #DC143C;
    }

    .border-crimson {
        border-color: #DC143C;
    }

    .bg-crimson {
        background-color: #DC143C;
    }

    .from-crimson {
        --tw-gradient-from: #DC143C;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(220, 20, 60, 0));
    }

    .font-montserrat {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>