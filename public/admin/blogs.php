<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';

if (!isAdmin()) {
    setFlashMessage('error', 'Unauthorized access.');
    redirect('../login.php');
}

$pageTitle = "Manage Blogs";
require_once __DIR__ . '/header.php';

$pdo = getDBConnection();
$statusFilter = $_GET['status'] ?? 'pending';
$stmt = $pdo->prepare("SELECT b.*, p.full_name as author_name FROM blogs b JOIN profiles p ON b.user_id = p.user_id WHERE b.status = ? ORDER BY b.created_at DESC");
$stmt->execute([$statusFilter]);
$blogs = $stmt->fetchAll();
?>

<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Blog Management</h1>
            <p class="mt-2 text-sm text-gray-700">Review and manage blog posts submitted by community.</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <?php foreach (['pending', 'approved', 'rejected'] as $status): ?>
                <a href="?status=<?php echo $status; ?>"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm <?php echo $statusFilter === $status ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?>">
                    <?php echo ucfirst($status); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-white border-b border-gray-200">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Post
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Author
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php if (empty($blogs)): ?>
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-sm text-gray-500">No
                                        <?php echo $statusFilter; ?> blogs found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($blogs as $blog): ?>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded object-cover bg-gray-100"
                                                        src="<?php echo !empty($blog['featured_image']) ? '../' . htmlspecialchars($blog['featured_image']) : 'https://via.placeholder.com/100x100?text=Blog'; ?>"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-bold text-gray-900">
                                                        <?php echo htmlspecialchars($blog['title']); ?>
                                                    </div>
                                                    <div class="text-gray-500">
                                                        <?php echo htmlspecialchars($blog['slug']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <?php echo htmlspecialchars($blog['author_name']); ?>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-2">
                                            <a href="blog-view.php?id=<?php echo $blog['id']; ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                            <?php if ($statusFilter !== 'approved'): ?>
                                                <form action="../api/admin/blog-status.php" method="POST" class="inline">
                                                    <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                                                    <input type="hidden" name="status" value="approved">
                                                    <input type="hidden" name="csrf_token"
                                                        value="<?php echo generateCSRFToken(); ?>">
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-900">Approve</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($statusFilter !== 'rejected'): ?>
                                                <form action="../api/admin/blog-status.php" method="POST" class="inline">
                                                    <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <input type="hidden" name="csrf_token"
                                                        value="<?php echo generateCSRFToken(); ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                </form>
                                            <?php endif; ?>

                                            <button
                                                onclick="if(confirm('Are you sure you want to delete this blog?')) { window.location.href='../api/admin/blog-status.php?action=delete&id=<?php echo $blog['id']; ?>&token=<?php echo generateCSRFToken(); ?>' }"
                                                class="text-gray-400 hover:text-gray-600">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>