<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';

if (!isAdmin()) {
    setFlashMessage('error', 'Unauthorized access.');
    redirect('../login.php');
}

$blogId = $_GET['id'] ?? '';

if (empty($blogId)) {
    setFlashMessage('error', 'Invalid blog ID.');
    redirect('blogs.php');
}

$pdo = getDBConnection();
// Fetch blog details including author info
$stmt = $pdo->prepare("SELECT b.*, p.full_name as author_name, p.photo_url as author_photo 
                       FROM blogs b 
                       JOIN profiles p ON b.user_id = p.user_id 
                       WHERE b.id = ?");
$stmt->execute([$blogId]);
$blog = $stmt->fetch();

if (!$blog) {
    setFlashMessage('error', 'Blog post not found.');
    redirect('blogs.php');
}

$pageTitle = "View Blog: " . htmlspecialchars($blog['title']);
require_once __DIR__ . '/header.php';
?>

<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Action Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <?php echo htmlspecialchars($blog['title']); ?>
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <?php echo htmlspecialchars($blog['author_name']); ?>
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm1 2h6v1H7V4z" clip-rule="evenodd" />
                    </svg>
                    Published on <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                </div>
                <div class="mt-2 flex items-center text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        <?php 
                        echo $blog['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                            ($blog['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); 
                        ?>">
                        <?php echo ucfirst($blog['status']); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="blogs.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Back
            </a>

            <?php if ($blog['status'] !== 'approved'): ?>
                <form action="../api/admin/blog-status.php" method="POST" class="inline">
                    <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                    <input type="hidden" name="status" value="approved">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($blog['status'] !== 'rejected'): ?>
                <form action="../api/admin/blog-status.php" method="POST" class="inline">
                    <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                    <input type="hidden" name="status" value="rejected">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Reject
                    </button>
                </form>
            <?php endif; ?>

            <button onclick="if(confirm('Are you sure you want to delete this blog?')) { window.location.href='../api/admin/blog-status.php?action=delete&id=<?php echo $blog['id']; ?>&token=<?php echo generateCSRFToken(); ?>' }"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete
            </button>
        </div>
    </div>

    <!-- Blog Content Preview -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Blog Content</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Full preview of the blog post.</p>
        </div>
        <div class="border-t border-gray-200">
            <!-- Featured Image -->
            <?php if (!empty($blog['featured_image'])): ?>
                <div class="w-full h-96 overflow-hidden">
                    <img src="<?php echo '../' . htmlspecialchars($blog['featured_image']); ?>" alt="Featured Image" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            
            <div class="px-4 py-5 sm:px-6 prose max-w-none">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
