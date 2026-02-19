<?php
// public/forgot-password.php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-white via-white to-white z-0"></div>

    <div class="max-w-md mx-auto px-6 relative z-10">
        <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 p-10 overflow-hidden">
            <div class="text-center mb-10">
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Account
                    Recovery</span>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-4">Forgot
                    Password?</h2>
                <p class="text-gray-500 font-medium">Enter your email to receive a password reset code.</p>
            </div>

            <?php if (hasFlashMessage('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium"><?php echo getFlashMessage('error'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (hasFlashMessage('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium"><?php echo getFlashMessage('success'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="api/auth/send_reset_otp.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium bg-gray-50 focus:bg-white"
                        placeholder="john@example.com">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest py-4 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Send Reset Code
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-gray-50 mt-6">
                    <p class="text-gray-500 font-medium text-sm">Remembered your password? <a href="login.php"
                            class="text-primary font-bold uppercase tracking-widest text-xs hover:underline ml-1">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
