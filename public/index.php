<?php
require_once __DIR__ . '/../includes/functions.php';
// Database Connection
require_once __DIR__ . '/../config/database.php';
$pdo = getDBConnection();

// 1. Fetch Latest Events (Optimized: only needed columns, upcoming events)
$stmtEvents = $pdo->prepare("
    SELECT id, title, slug, description, event_date, image_url 
    FROM events 
    WHERE event_date >= CURDATE() 
    ORDER BY event_date ASC 
    LIMIT 3
");
$stmtEvents->execute();
$latestEvents = $stmtEvents->fetchAll();

// 2. Fetch Latest Blogs (Optimized: explicit column selection)
$stmtBlogs = $pdo->prepare("
    SELECT 
        b.id, b.title, b.slug, b.excerpt, b.content, b.featured_image, b.created_at,
        p.full_name as author_name, p.photo_url as author_photo 
    FROM blogs b 
    INNER JOIN profiles p ON b.user_id = p.user_id 
    WHERE b.status = 'approved' 
    ORDER BY b.created_at DESC 
    LIMIT 3
");
$stmtBlogs->execute();
$latestBlogs = $stmtBlogs->fetchAll();


$pageTitle = "Home";
require_once __DIR__ . '/../includes/header.php';


?>

<div class="min-h-screen">
    <!-- Elite Cinematic Hero Section -->
    <div class="relative bg-black h-[100dvh] w-full overflow-hidden will-change-transform">
        <!-- Background fog effect with clean, crisp animation -->
        <!-- DISABLED: Canvas animation causes scroll lag. Uncomment to re-enable:
            <canvas id="fog-overlay" class="absolute inset-0 w-full h-full pointer-events-none"
                style="mix-blend-mode: overlay; opacity: 0.6;" data-enable="true"></canvas>
            -->

        <div class="swiper hero-swiper h-full w-full">
            <div class="swiper-wrapper">
                <!-- Upper Section -->
                <div class="swiper-slide relative h-full w-full">
                    <div class="absolute inset-0">
                        <img src="<?php echo $basePath; ?>img/b.webp"
                            class="w-full h-full object-cover grayscale-[15%] contrast-[1.1] brightness-[0.7]"
                            alt="Education">
                    </div>
                    <div
                        class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black via-black/40 to-transparent">
                    </div>
                    <div
                        class="relative z-30 h-full flex flex-col justify-center items-center px-8 text-center max-w-7xl mx-auto">
                        <span
                            class="text-white font-black tracking-[0.4em] text-xs md:text-base bg-secondary px-4 py-1 md:px-6 md:py-2 rounded-full border border-white/10 mb-6 md:mb-12 block w-fit mx-auto"
                            data-aos="fade-down">A Global Movement</span>
                        <h1 class="text-3xl md:text-5xl font-black text-white mb-6 md:mb-8 leading-[1.1] tracking-tighter"
                            data-aos="zoom-out" data-aos-delay="200">
                            Uniting Youth<br> For <br>
                            <span
                                class="text-3xl md:text-5xl font-black text-black mb-6 md:mb-8 leading-[1] tracking-tighter">National
                                Development</span>
                        </h1>
                        <p class="text-gray-300 max-w-2xl text-base md:text-xl mb-8 md:mb-12 font-medium leading-relaxed opacity-90 px-4"
                            data-aos="fade-up" data-aos-delay="400">
                            Connecting, Inspiring, & channeling global community of Nepali directly into mainstream
                            development of Nepal.
                            Make your voice heard, Make your effort count, Make your liife meaningful.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-6" data-aos="fade-up" data-aos-delay="600">
                            <a href="<?php echo $basePath; ?>register.php"
                                class="bg-primary hover:bg-red-700 text-white font-black uppercase tracking-widest text-xs px-10 py-5 rounded-2xl shadow-glow transition-all transform active:scale-95">
                                Join the Movement
                            </a>
                            <a href="<?php echo $basePath; ?>about.php"
                                class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-black uppercase tracking-widest text-xs px-10 py-5 rounded-2xl border border-white/20 transition-all">
                                Our Vision
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Minimalist Navigation -->
            <div class="swiper-pagination !bottom-12"></div>
        </div>
    </div>

    <!-- Impact Analytics Section (Stats) -->
    <section class="relative py-16 md:py-32 overflow-hidden">
        <!-- Background Insight Illustration -->
        <div class="absolute inset-0 z-0">
            <img src="<?php echo $basePath; ?>img/insight.png" alt=""
                class="w-full h-full object-cover opacity-[0.15] pointer-events-none">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">Our Impact</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 md:gap-12">
                <div class="text-center group" data-aos="fade-up">
                    <div
                        class="text-5xl md:text-7xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors tabular-nums">
                        200+</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Active Members</p>
                </div>
                <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="text-5xl md:text-7xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors tabular-nums">
                        20+</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Countries Represented</p>
                </div>
                <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="text-5xl md:text-7xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors tabular-nums">
                        20+</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Communities & Forums</p>
                </div>
                <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="text-5xl md:text-7xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors tabular-nums">
                        24/7</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Global Connectivity</p>
                </div>
                <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="text-5xl md:text-7xl font-black text-gray-900 mb-2 group-hover:text-primary transition-colors tabular-nums">
                        50+</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Events</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Pillars Grid -->
    <section class="py-16 md:py-32 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">How we work</span>
                <img src="<?php echo $basePath; ?>img/Tree.webp" alt="GYAN Tree"
                    class="mx-auto h-20 md:h-28 w-auto my-6 object-contain" data-aos="fade-up" data-aos-delay="50">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">GYAN Philosophy
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-10 relative z-10">
                <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                    data-aos="fade-right">
                    <div
                        class="w-16 h-16 bg-white border border-red-100 text-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Connect</h3>
                    <p class="text-gray-500 leading-relaxed">Bridging the gap between global Nepali youth to foster
                        collaboration, share resources, and build a powerful, united network.</p>
                </div>
                <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                    data-aos="fade-up">
                    <div
                        class="w-16 h-16 bg-white border border-blue-100 text-secondary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-secondary group-hover:text-white transition-all duration-500">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Inspire</h3>
                    <p class="text-gray-500 leading-relaxed">Empowering the next generation through visionary mentorship
                        and professional guidance from established global leaders.</p>
                </div>
                <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                    data-aos="fade-left">
                    <div
                        class="w-16 h-16 bg-white border border-green-100 text-accent rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-white transition-all duration-500">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307L20.25 7.5M20.25 7.5H15.75M20.25 7.5v4.5" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Impact</h3>
                    <p class="text-gray-500 leading-relaxed">Driving tangible national development through innovative
                        projects, community partnership, and collective youth action.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Latest Events Section -->
    <section class="py-12 md:py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-secondary font-black uppercase tracking-widest text-xs mb-4 block">Connect &
                    Engage</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">Latest Events</h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto text-lg">Join us at our upcoming gatherings and make an
                    impact.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php if (!empty($latestEvents)): ?>
                    <?php foreach ($latestEvents as $event): ?>
                        <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border-[3px] border-[#8B0000] overflow-hidden"
                            data-aos="fade-up">
                            <div class="relative h-56 overflow-hidden">
                                <img src="<?php echo !empty($event['image_url']) ? $basePath . 'uploads/events/' . basename($event['image_url']) : $basePath . 'img/hero_1.png'; ?>"
                                    alt="<?php echo htmlspecialchars($event['title']); ?>" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-5">
                                    <span
                                        class="text-xs font-black text-white uppercase tracking-widest bg-primary px-3 py-1 rounded-md">
                                        <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="p-8">
                                <h3
                                    class="text-2xl font-black text-gray-900 mb-3 leading-tight group-hover:text-primary transition-colors ">
                                    <a href="<?php echo $basePath; ?>event.php?slug=<?php echo $event['slug'] ?? '#'; ?>">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                    <?php echo htmlspecialchars(substr(strip_tags($event['description']), 0, 120)) . '...'; ?>
                                </p>
                                <a href="<?php echo $basePath; ?>event.php?slug=<?php echo $event['slug'] ?? '#'; ?>"
                                    class="inline-flex items-center text-secondary font-black uppercase tracking-widest text-xs group-hover:text-primary transition-colors">
                                    View Event
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-lg">No upcoming events at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Latest Blogs Section -->
    <section class="py-12 md:py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-16 text-center" data-aos="fade-up">
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Knowledge Hub</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">Latest Insights
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php if (!empty($latestBlogs)): ?>
                    <?php foreach ($latestBlogs as $blog): ?>
                        <article
                            class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border-[3px] border-[#8B0000] overflow-hidden flex flex-col h-full"
                            data-aos="fade-up">
                            <div class="relative h-52 overflow-hidden">
                                <img src="<?php echo !empty($blog['featured_image']) ? htmlspecialchars($blog['featured_image']) : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800q=80'; ?>"
                                    alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            </div>
                            <div class="p-8 flex-1 flex flex-col">
                                <div
                                    class="flex items-center text-xs text-gray-400 font-bold uppercase tracking-wider mb-4 gap-3">
                                    <span
                                        class="text-primary"><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span><?php echo htmlspecialchars($blog['author_name']); ?></span>
                                </div>
                                <h3
                                    class="text-xl font-black text-gray-900 mb-4 leading-tight group-hover:text-primary transition-colors  line-clamp-2">
                                    <a href="<?php echo $basePath; ?>blog-view.php?slug=<?php echo $blog['slug']; ?>">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed flex-1">
                                    <?php echo htmlspecialchars($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 100) . '...'); ?>
                                </p>
                                <a href="<?php echo $basePath; ?>blog-view.php?slug=<?php echo $blog['slug']; ?>"
                                    class="inline-block text-xs font-black uppercase tracking-widest text-gray-900 border-b-2 border-gray-200 hover:border-primary pb-1 transition-all">
                                    Read Article
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-lg">Our thought leaders are writing updates.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-10 text-center md:hidden">
                <a href="<?php echo $basePath; ?>blog.php"
                    class="inline-flex items-center text-primary font-bold uppercase tracking-widest text-xs">
                    Read All Stories <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 md:py-24 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-accent font-black uppercase tracking-widest text-xs mb-4 block">Community
                    Voices</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">Impact Stories</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl p-8 border-[3px] border-[#8B0000] relative group hover:shadow-xl transition-all"
                    data-aos="fade-up" data-aos-delay="0">
                    <div
                        class="absolute -top-6 left-8 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center  text-3xl shadow-lg leading-none">
                        â€œ</div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 mt-4 italic">
                        "Joining GYAN opened doors I didn't know existed. The mentorship program connected me with
                        industry leaders who helped shape my career."
                    </p>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6">
                        <img src="<?php echo $basePath; ?>img/person_1.png" alt="Member" loading="lazy" decoding="async"
                            width="40" height="40"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-md">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-none ">Priya Adhikari</h4>
                            <span
                                class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Entrepreneur</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl p-8 border-[3px] border-[#8B0000] relative group hover:shadow-xl transition-all"
                    data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="absolute -top-6 left-8 w-12 h-12 bg-secondary text-white rounded-full flex items-center justify-center  text-3xl shadow-lg leading-none">
                        â€œ</div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 mt-4 italic">
                        "The network here is unparalleled. I found my co-founder through a GYAN community event in
                        Kathmandu."
                    </p>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6">
                        <img src="<?php echo $basePath; ?>img/person_2.png" alt="Member" loading="lazy" decoding="async"
                            width="40" height="40"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-md">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-none ">Aarav Sharma</h4>
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Tech Lead</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-2xl p-8 border-[3px] border-[#8B0000] relative group hover:shadow-xl transition-all"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="absolute -top-6 left-8 w-12 h-12 bg-accent text-white rounded-full flex items-center justify-center  text-3xl shadow-lg leading-none">
                        â€œ</div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 mt-4 italic">
                        "GYAN allows us to give back to our homeland in a structured, impactful way. It's more than just
                        a community; it's a movement."
                    </p>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6">
                        <img src="<?php echo $basePath; ?>img/person_3.png" alt="Member" loading="lazy" decoding="async"
                            width="40" height="40"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-md">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-none ">Bibek Thapa</h4>
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Social
                                Worker</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="bg-white rounded-2xl p-8 border-[3px] border-[#8B0000] relative group hover:shadow-xl transition-all"
                    data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="absolute -top-6 left-8 w-12 h-12 bg-gray-900 text-white rounded-full flex items-center justify-center  text-3xl shadow-lg leading-none">
                        â€œ</div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 mt-4 italic">
                        "As a student abroad, GYAN gave me a sense of belonging and purpose. The educational resources
                        are top-notch."
                    </p>
                    <div class="flex items-center gap-4 border-t border-gray-200 pt-6">
                        <img src="<?php echo $basePath; ?>img/person_4.png" alt="Member" loading="lazy" decoding="async"
                            width="40" height="40"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-md">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm leading-none ">Aman Karki</h4>
                            <span
                                class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Researcher</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- High Impact Call to Action -->
    <section class="relative py-40 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="<?php echo $basePath; ?>img/hero_2.webp?v=1.0.0" class="w-full h-full object-cover" loading="lazy"
                decoding="async" alt="Ready to Ascend background">
            <div class="absolute inset-0 bg-black/70"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/40 to-black"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center" data-aos="zoom-in">
            <h2 class="text-4xl md:text-8xl font-black text-white mb-6 md:mb-10 tracking-tighter uppercase">Ready to
                Ascend?
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-8 md:mb-14 font-medium max-w-2xl mx-auto leading-relaxed">
                Join our Global Alliance of Nepalis and be part of the most influential youth network shaping the future
                of our nation.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="<?php echo $basePath; ?>register.php"
                    class="bg-primary hover:bg-red-700 text-white font-black uppercase tracking-widest text-xs px-12 py-6 rounded-2xl shadow-glow transition-all transform active:scale-95">
                    Become a Member
                </a>
                <a href="<?php echo $basePath; ?>contact.php"
                    class="bg-white text-gray-900 hover:bg-gray-100 font-black uppercase tracking-widest text-xs px-12 py-6 rounded-2xl shadow-xl transition-all">
                    Partner With Us
                </a>
            </div>
        </div>
    </section>
</div>

<!-- AOS Library Initialization is handled in header/footer, but we ensure local trigger -->


<?php require_once __DIR__ . '/../includes/footer.php'; ?>