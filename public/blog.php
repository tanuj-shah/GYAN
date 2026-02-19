<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$pageTitle = "Insights & Stories";
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT b.*, p.full_name as author_name, p.photo_url as author_photo 
                       FROM blogs b 
                       JOIN profiles p ON b.user_id = p.user_id 
                       WHERE b.status = 'approved' 
                       ORDER BY b.created_at DESC");
$stmt->execute();
$blogs = $stmt->fetchAll();

$featured = !empty($blogs) ? array_shift($blogs) : null;
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
                Insights & Growth</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">The GYAN Blog</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                Explore the thoughts, stories, and leadership perspectives from our vibrant global community of Nepalis.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-12 md:pb-24">

        <?php if ($featured): ?>
            <!-- Cinematic Featured Spotlight -->
            <div class="mb-20" data-aos="zoom-in" data-aos-duration="1000">
                <article
                    class="bg-white rounded-[2.5rem] shadow-premium overflow-hidden border-[3px] border-[#8B0000] flex flex-col lg:flex-row group transition-all duration-500 hover:shadow-2xl">
                    <div class="lg:w-3/5 relative h-96 lg:h-auto overflow-hidden">
                        <img src="<?php echo !empty($featured['featured_image']) ? htmlspecialchars($featured['featured_image']) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200q=80'; ?>"
                            alt="Featured Story"
                            class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-[3000ms]">
                        <div class="absolute inset-0 bg-black/50 lg:hidden">
                        </div>
                        <div class="absolute top-8 left-8">
                            <span
                                class="px-5 py-2 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-glow">
                                Master Story
                            </span>
                        </div>
                    </div>
                    <div class="lg:w-2/5 p-6 md:p-14 flex flex-col justify-center bg-white relative">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="relative">
                                <img src="<?php echo !empty($featured['author_photo']) ? htmlspecialchars(ltrim($featured['author_photo'], '/')) : 'https://ui-avatars.com/api/?name=' . urlencode($featured['author_name']) . '&background=random'; ?>"
                                    class="w-12 h-12 rounded-full border-2 border-primary/20 shadow-sm p-0.5">
                                <div
                                    class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full">
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none mb-1">
                                    <?php echo htmlspecialchars($featured['author_name']); ?>
                                </p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <?php echo date('M d, Y', strtotime($featured['created_at'])); ?>
                                </p>
                            </div>
                        </div>

                        <h2
                            class="text-3xl md:text-4xl font-black text-gray-900 mb-6 leading-tight group-hover:text-primary transition-colors">
                            <a href="blog-view.php?slug=<?php echo $featured['slug']; ?>">
                                <?php echo htmlspecialchars($featured['title']); ?>
                            </a>
                        </h2>

                        <p class="text-gray-500 mb-10 text-lg leading-relaxed line-clamp-3">
                            <?php echo htmlspecialchars($featured['excerpt'] ?: substr(strip_tags($featured['content']), 0, 180) . '...'); ?>
                        </p>

                        <div class="mt-auto">
                            <a href="blog-view.php?slug=<?php echo $featured['slug']; ?>"
                                class="inline-flex items-center text-primary font-black uppercase tracking-widest text-xs group/btn">
                                Immerse in Story
                                <svg class="w-5 h-5 ml-3 transform group-hover/btn:translate-x-3 transition-transform duration-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        <?php endif; ?>

        <!-- Latest Insights Grid -->
        <div class="mb-10 flex items-center justify-between" data-aos="fade-up">
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-widest">
                <span class="text-primary mr-2">/</span>Latest Insights
            </h3>
        </div>

        <?php if (empty($blogs)): ?>
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-20 text-center" data-aos="fade-up">
                <div
                    class="w-20 h-20 bg-white border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-2">The desk is clear.</h4>
                <p class="text-gray-500 max-w-xs mx-auto text-sm leading-relaxed">Our writers are deep in thought. New
                    insights will be added to the collection soon.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php foreach ($blogs as $index => $blog): ?>
                    <article
                        class="bg-white rounded-[2rem] shadow-sm border-[3px] border-[#8B0000] overflow-hidden group hover:shadow-2xl transition-all duration-500 flex flex-col"
                        data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">

                        <div class="h-48 relative overflow-hidden">
                            <img src="<?php echo !empty($blog['featured_image']) ? htmlspecialchars($blog['featured_image']) : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800q=80'; ?>"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0"></div>

                            <div class="absolute bottom-6 left-6">
                                <span
                                    class="px-3 py-1 bg-white/95 backdrop-blur-md rounded-lg text-[10px] font-black uppercase tracking-tighter text-primary">
                                    Editorial
                                </span>
                            </div>
                        </div>

                        <div class="py-3 px-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-[10px] font-black text-primary/60 uppercase tracking-widest">Thought
                                    Leadership</span>
                                <span class="h-1 w-1 bg-gray-200 rounded-full"></span>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                            </div>

                            <h4
                                class="text-xl font-extrabold text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                <a
                                    href="blog-view.php?slug=<?php echo $blog['slug']; ?>"><?php echo htmlspecialchars($blog['title']); ?></a>
                            </h4>

                            <p class="text-gray-500 text-sm mb-2 line-clamp-3 leading-relaxed">
                                <?php echo htmlspecialchars($blog['excerpt'] ?: substr(strip_tags($blog['content']), 0, 120) . '...'); ?>
                            </p>

                            <div class="mt-auto pt-2 border-t border-gray-50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo !empty($blog['author_photo']) ? htmlspecialchars(ltrim($blog['author_photo'], '/')) : 'https://ui-avatars.com/api/?name=' . urlencode($blog['author_name']) . '&background=random'; ?>"
                                        class="w-7 h-7 rounded-full border border-gray-100 shadow-inner">
                                    <span
                                        class="text-[10px] font-bold text-gray-700 uppercase tracking-wider"><?php echo htmlspecialchars($blog['author_name']); ?></span>
                                </div>
                                <a href="blog-view.php?slug=<?php echo $blog['slug']; ?>"
                                    class="w-8 h-8 rounded-full border border-gray-100 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 transform group-hover:rotate-[360deg]">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Premium Newsletter Section -->
<section class="border-t border-gray-100 py-16 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 z-0 opacity-[0.03]">
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary rounded-full filter blur-3xl translate-x-1/2 -translate-y-1/2">
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 text-center relative z-10" data-aos="fade-up">
        <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Our Newsletter</span>
        <h2 class="text-4xl font-black text-gray-900 mb-6 uppercase tracking-widest">Join the Alliance Insider</h2>
        <p class="text-gray-500 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
            Curated updates, event highlights, and impactful community stories delivered directly to your professional
            orbit.
        </p>
        <form
            class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto bg-white p-2 rounded-3xl border border-gray-100 shadow-sm">
            <input type="email" placeholder="professional@email.com"
                class="flex-grow px-8 py-4 bg-transparent border-none focus:ring-0 text-gray-900 placeholder-gray-400 font-medium">
            <button type="submit"
                class="px-10 py-4 bg-primary text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-glow hover:bg-primary-dark transition-all transform active:scale-95">
                Subscribe
            </button>
        </form>
    </div>
</section>

<!-- AOS Animation Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                easing: 'ease-out-quint'
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>