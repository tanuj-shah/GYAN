<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();

// Fetch events with latest 4 images
$stmt = $pdo->query("SELECT * FROM gallery_events ORDER BY created_at DESC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($events)) {
    foreach ($events as &$evt) {
        $stmt = $pdo->prepare("SELECT filename, alt_text FROM gallery_images WHERE event_id = ? ORDER BY is_featured DESC, order_index ASC, created_at DESC LIMIT 4");
        $stmt->execute([$evt['id']]);
        $evt['previews'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($evt); // Important: break the reference
}
?>

<div class="min-h-screen">
    <!-- Hero Section -->
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
                The Gallery</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Visual Stories</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                Experience the impact of the Global Youth Alliance for Nepal through our shared visual journey.
            </p>
        </div>
    </div>

    <!-- Events List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-12 md:pb-24">
        <div
            class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-premium overflow-hidden border border-gray-100 p-6 md:p-20">
            <?php if (empty($events)): ?>
                <div class="text-center py-20">
                    <p class="text-gray-400 text-lg">No albums found.</p>
                </div>
            <?php else: ?>
                <div class="space-y-12 md:space-y-16">
                    <?php foreach ($events as $index => $evt): ?>
                        <?php if (empty($evt['previews']))
                            continue; ?>

                        <section class="group">
                            <!-- 1. Event Title (Centered) -->
                            <div class="text-center mb-8">
                                <span class="text-primary font-black uppercase tracking-widest text-xs mb-3 block">Event
                                    Album</span>
                                <h2
                                    class="text-3xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tighter leading-none">
                                    <?php echo htmlspecialchars($evt['title']); ?>
                                </h2>
                                <?php if (!empty($evt['description'])): ?>
                                    <p class="text-gray-500 text-lg leading-relaxed max-w-3xl mx-auto italic">
                                        "<?php echo htmlspecialchars($evt['description']); ?>"
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- 2. Four Images -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                <?php foreach ($evt['previews'] as $img): ?>
                                    <div
                                        class="relative aspect-[4/5] overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 group-hover:border-primary/20 border border-gray-100">
                                        <img src="uploads/gallery/<?php echo $evt['id']; ?>/<?php echo htmlspecialchars($img['filename']); ?>"
                                            alt="<?php echo htmlspecialchars($img['alt_text'] ?? $evt['title']); ?>"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                            loading="lazy">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- 3. See More Button -->
                            <div class="text-center">
                                <a href="event-gallery.php?id=<?php echo $evt['id']; ?>"
                                    class="inline-flex items-center space-x-3 text-primary font-black uppercase tracking-widest hover:text-secondary transition-colors duration-300 group/btn">
                                    <span>Explore Gallery</span>
                                    <svg class="w-5 h-5 transform group-hover/btn:translate-x-2 transition-transform duration-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </section>

                        <?php if ($index < count($events) - 1): ?>
                            <div class="relative py-8">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-100"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="bg-white px-4 text-gray-300">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>