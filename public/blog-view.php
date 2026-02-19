<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    redirect('blog.php');
}

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT b.*, p.full_name as author_name, p.photo_url as author_photo, p.bio as author_bio, p.profession as author_profession 
                       FROM blogs b 
                       JOIN profiles p ON b.user_id = p.user_id 
                       WHERE b.slug = ? AND b.status = 'approved'");
$stmt->execute([$slug]);
$blog = $stmt->fetch();

if (!$blog) {
    setFlashMessage('error', 'Blog post not found or not approved.');
    redirect('blog.php');
}

$pageTitle = $blog['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="bg-white pb-12 md:pb-20 pt-24 lg:pt-32">
    <!-- Progress Bar -->
    <div class="fixed top-0 left-0 w-full h-1 z-[60]">
        <div id="blog-progress" class="h-full bg-primary transition-all duration-150" style="width: 0%"></div>
    </div>

    <!-- Article Header -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="index.php"
                        class="text-xs font-bold text-gray-500 hover:text-primary uppercase tracking-widest transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="blog.php"
                            class="ml-1 text-xs font-bold text-gray-500 hover:text-primary uppercase tracking-widest transition-colors">Blog</a>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6 md:mb-8">
            <?php echo htmlspecialchars($blog['title']); ?>
        </h1>

        <div class="flex flex-wrap items-center gap-6 py-8 border-y border-gray-100">
            <div class="flex items-center gap-3">
                <img src="<?php echo !empty($blog['author_photo']) ? htmlspecialchars(ltrim($blog['author_photo'], '/')) : 'https://ui-avatars.com/api/?name=' . urlencode($blog['author_name']) . '&background=random'; ?>"
                    class="w-12 h-12 rounded-full border-2 border-primary/10 p-0.5">
                <div>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo htmlspecialchars($blog['author_name']); ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        <?php echo htmlspecialchars($blog['author_profession'] ?: 'Community Member'); ?>
                    </p>
                </div>
            </div>
            <div class="h-10 w-px bg-gray-100 hidden sm:block"></div>
            <div class="text-sm text-gray-500">
                <p class="font-medium">Published</p>
                <p>
                    <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                </p>
            </div>
            <div class="h-10 w-px bg-gray-100 hidden sm:block"></div>
            <div class="text-sm text-gray-500">
                <p class="font-medium">Read Time</p>
                <p>
                    <?php echo ceil(str_word_count(strip_tags($blog['content'])) / 200); ?> min read
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Image -->
    <?php if (!empty($blog['featured_image'])): ?>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="rounded-[2rem] overflow-hidden shadow-2xl aspect-video lg:aspect-[21/9]">
                <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" class="w-full h-full object-cover"
                    alt="Blog Featured Image">
            </div>
        </div>
    <?php endif; ?>

    <!-- Article Content -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <article
            class="prose prose-lg prose-primary max-w-none prose-headings: prose-headings:font-bold prose-p:text-gray-700 prose-p:leading-relaxed prose-a:text-primary prose-a:no-underline hover:prose-a:underline">
            <?php
            // If content has HTML (from an editor), output it. For now, we assume simple text and use nl2br
            // In a real app, we'd use a markdown parser or a rich text editor.
            echo nl2br(htmlspecialchars($blog['content']));
            ?>
        </article>

        <!-- Author Bio Section -->
        <div
            class="mt-12 md:mt-20 p-6 md:p-12 bg-neutral-50 rounded-2xl md:rounded-3xl border border-gray-100 flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start text-center md:text-left">
            <img src="<?php echo !empty($blog['author_photo']) ? htmlspecialchars(ltrim($blog['author_photo'], '/')) : 'https://ui-avatars.com/api/?name=' . urlencode($blog['author_name']) . '&background=random'; ?>"
                class="w-24 h-24 rounded-2xl shadow-lg flex-shrink-0">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">About the Author</h3>
                <h4 class="text-lg  font-bold text-primary mb-4">
                    <?php echo htmlspecialchars($blog['author_name']); ?>
                </h4>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    <?php echo htmlspecialchars($blog['author_bio'] ?: 'A passionate member of the Global Youth Alliance for Nepal, contributing to the community through insights and stories.'); ?>
                </p>
                <div class="flex justify-center md:justify-start">
                    <a href="directory.php"
                        class="text-sm font-bold text-gray-900 hover:text-primary transition-colors flex items-center gap-2 group">
                        View Profile
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Share & Back -->
        <div class="mt-16 flex flex-col sm:flex-row items-center justify-between gap-6 pt-12 border-t border-gray-100">
            <a href="blog.php"
                class="inline-flex items-center text-gray-500 hover:text-primary font-bold transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                </svg>
                Back to Blog
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Share this</span>
                <div class="flex gap-2">
                    <button
                        class="p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-primary hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </button>
                    <button
                        class="p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-primary hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Scroll Progress Bar
    window.onscroll = function () {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById("blog-progress").style.width = scrolled + "%";
    };
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>