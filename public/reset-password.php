<?php
// public/reset-password.php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// Security check
if (!isset($_SESSION['reset_verified']) || $_SESSION['reset_verified'] !== true || !isset($_SESSION['reset_email'])) {
    setFlashMessage('error', 'Unauthorized access to password reset');
    redirect('forgot-password.php');
}
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-white via-white to-white z-0"></div>

    <div class="max-w-md mx-auto px-6 relative z-10">
        <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 p-10 overflow-hidden">
            <div class="text-center mb-10">
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Securing Your
                    Account</span>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-4">New Password
                </h2>
                <p class="text-gray-500 font-medium">Create a strong password for your account.</p>
            </div>

            <form class="space-y-6" action="api/auth/reset_password.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <!-- Password -->
                <div>
                    <label for="password"
                        class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">New Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium bg-gray-50 focus:bg-white">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm"
                        class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">Confirm Password
                        <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirm" name="password_confirm" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium bg-gray-50 focus:bg-white">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest py-4 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
