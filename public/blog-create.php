<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to submit a blog.');
    redirect('login.php');
}

$pageTitle = "Submit Blog";
require_once __DIR__ . '/../includes/header.php';

// Fetch user's existing blogs
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$userBlogs = $stmt->fetchAll();
?>

<div class="min-h-screen py-10 pt-24 lg:pt-32">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="dashboard.php" class="text-sm text-gray-500 hover:text-primary">Dashboard</a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Submit Blog</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl  font-bold text-gray-900">Share Your Story</h1>
            <p class="mt-2 text-gray-600">Your blog will be reviewed by our team before being published on the GYAN blog
                page.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <form action="api/blog/create.php" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
                    <input type="text" name="title" id="title" required placeholder="Enter a catchy title for your blog"
                        class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-primary focus:border-primary shadow-sm transition-all">
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Short Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="2"
                        placeholder="A brief summary that appears on the blog listing page..."
                        class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-primary focus:border-primary shadow-sm transition-all"></textarea>
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured
                        Image</label>
                    <div id="image-upload-container"
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary transition-colors cursor-pointer group relative overflow-hidden"
                        onclick="document.getElementById('featured_image').click()">
                        <div id="upload-placeholder" class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors"
                                stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <span id="upload-text"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark">Upload
                                    a file</span>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                        </div>
                        <div id="image-preview-container"
                            class="hidden absolute inset-0 w-full h-full bg-white flex items-center justify-center p-2">
                            <img id="image-preview" src="#" alt="Preview"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-sm">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span
                                    class="bg-white/90 text-gray-900 px-3 py-1.5 rounded-full text-xs font-bold shadow-soft">Change
                                    Image</span>
                            </div>
                        </div>
                        <input id="featured_image" name="featured_image" type="file" class="sr-only" accept="image/*"
                            onchange="previewImage(this)">
                    </div>
                </div>

                <script>
                    function previewImage(input) {
                        const placeholder = document.getElementById('upload-placeholder');
                        const previewContainer = document.getElementById('image-preview-container');
                        const previewImage = document.getElementById('image-preview');
                        const uploadText = document.getElementById('upload-text');

                        if (input.files && input.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function (e) {
                                previewImage.src = e.target.result;
                                placeholder.classList.add('hidden');
                                previewContainer.classList.remove('hidden');
                            }

                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                </script>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Blog Content</label>
                    <textarea name="content" id="content" rows="10" required
                        placeholder="Write your blog content here..."
                        class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-primary focus:border-primary shadow-sm transition-all"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-4">
                    <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-xl shadow-glow text-white bg-primary hover:bg-primary-dark transition-all transform hover:-translate-y-0.5">
                        Submit for Review
                    </button>
                </div>
            </form>
        </div>

        <!-- Submission History Section -->
        <div class="mt-12 mb-12 md:mb-20" data-aos="fade-up">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">All Your Stories</h2>
                    <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Tracking your editorial
                        journey</p>
                </div>
            </div>

            <?php if (empty($userBlogs)): ?>
                <div class="bg-white rounded-[2.5rem] p-20 text-center border border-gray-100 shadow-premium">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 2v4a2 2 0 002 2h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No stories yet.</h3>
                    <p class="text-gray-400 text-sm max-w-xs mx-auto">Once you submit your first story, it will appear here
                        for you to track and manage.</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-premium overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    <th class="px-8 py-6">Story Detail</th>
                                    <th class="px-8 py-6">Status</th>
                                    <th class="px-8 py-6 text-right">Submitted Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php foreach ($userBlogs as $blog): ?>
                                    <tr class="group hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-black text-gray-900 group-hover:text-primary transition-colors">
                                                <?php echo htmlspecialchars($blog['title']); ?>
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                                /blog/<?php echo htmlspecialchars($blog['slug']); ?>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <?php
                                            $statusClasses = [
                                                'Published' => 'bg-green-50 text-green-600 border-green-100',
                                                'Draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                                'Pending' => 'bg-blue-50 text-blue-600 border-blue-100'
                                            ];
                                            $sClass = $statusClasses[$blog['status']] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                            ?>
                                            <span
                                                class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border <?php echo $sClass; ?>">
                                                <?php echo $blog['status']; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <span class="text-xs font-bold text-gray-500 uppercase">
                                                <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>