<?php
require_once __DIR__ . '/header.php';
$users = getAllUsers();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">User Management</h1>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-white text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-white text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Role
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-white text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Status
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-white text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Created At
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-white text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php echo htmlspecialchars($user['full_name'] ?: 'No Profile'); ?>
                                </p>
                                <p class="text-gray-500 whitespace-no-wrap text-xs">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            <?php echo ucfirst($user['role']); ?>
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span
                            class="relative inline-block px-3 py-1 font-semibold leading-tight 
                        <?php echo $user['status'] === 'active' ? 'text-green-900' : ($user['status'] === 'suspended' ? 'text-red-900' : 'text-yellow-900'); ?>">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full 
                            <?php echo $user['status'] === 'active' ? 'bg-green-200' : ($user['status'] === 'suspended' ? 'bg-red-200' : 'bg-yellow-200'); ?>">
                            </span>
                            <span class="relative">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">
                            <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <form action="../api/admin/update_user.php" method="POST" class="inline-block">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <?php if ($user['status'] !== 'active'): ?>
                                <button type="submit" name="status" value="active"
                                    class="text-green-600 hover:text-green-900 mr-2 text-xs font-bold uppercase">Approve</button>
                            <?php endif; ?>

                            <?php if ($user['status'] !== 'suspended' && $user['role'] !== 'admin'): ?>
                                <button type="submit" name="status" value="suspended"
                                    class="text-red-600 hover:text-red-900 text-xs font-bold uppercase">Suspend</button>
                            <?php endif; ?>
                        </form>

                        <?php if ($user['role'] !== 'admin'): ?>
                            <form action="../api/admin/delete_user.php" method="POST" class="inline-block ml-4"
                                onsubmit="return confirm('Initiate permanent removal? This action cannot be undone.');">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 text-xs font-black uppercase tracking-widest">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>
</div>
</body>

</html>