<?php
// public/verify-reset.php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-white via-white to-white z-0"></div>

    <div class="max-w-md mx-auto px-6 relative z-10">
        <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 p-10 overflow-hidden">
            <div class="text-center mb-10">
                <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block">Password
                    Recovery</span>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-4">Enter Code
                </h2>
                <p class="text-gray-500 font-medium">Please enter the 6-digit code sent to your email.</p>
            </div>

            <?php if (hasFlashMessage('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <p class="text-sm text-red-700 font-medium">
                        <?php echo getFlashMessage('error'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (hasFlashMessage('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <p class="text-sm text-green-700 font-medium">
                        <?php echo getFlashMessage('success'); ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="api/auth/verify_reset_otp.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <?php $email = $_GET['email'] ?? ''; ?>
                <div class="<?php echo !empty($email) ? 'hidden' : ''; ?>">
                    <label for="email"
                        class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-medium bg-gray-50 focus:bg-white"
                        placeholder="john@example.com" <?php echo !empty($email) ? 'readonly' : 'required'; ?>>
                </div>

                <!-- OTP Code -->
                <div>
                    <label for="otp"
                        class="block text-sm font-black text-gray-900 uppercase tracking-wider mb-3">Verification Code
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="otp" name="otp" required maxlength="6"
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-primary focus:ring-0 transition-colors font-black text-center text-2xl tracking-[0.5em] bg-gray-50 focus:bg-white"
                        placeholder="000000" pattern="[0-9]{6}" inputmode="numeric">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest py-4 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Verify Code
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
