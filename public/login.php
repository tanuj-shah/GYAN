<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = "Login";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen relative overflow-hidden bg-white">
    <!-- Premium High-Class Backdrop (Synced with About/Register) -->
    <div class="absolute inset-0 z-0">
        <!-- Mountain Image -->
        <img src="img/mountain.webp" alt=""
            class="absolute inset-0 w-full h-[120%] object-cover object-top opacity-20 -translate-y-12">
        <!-- Radial Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-white via-transparent to-blue-50/30"></div>
    </div>

    <!-- Decorative Framing Flags -->
    <div class="absolute inset-0 pointer-events-none hidden md:block select-none overflow-hidden" aria-hidden="true">
        <img src="img/flag.webp" alt=""
            class="absolute left-[-2rem] lg:left-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-50 object-contain"
            loading="lazy">
        <img src="img/flag.webp" alt=""
            class="absolute right-[-2rem] lg:right-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-40 object-contain scale-x-[-1]"
            loading="lazy">
    </div>

    <div class="relative z-10 min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8" data-aos="fade-up">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <div class="inline-flex items-center justify-center space-x-2 mb-6">
                <div class="h-1 w-8 bg-primary"></div>
                <p class="text-xs font-bold text-primary uppercase tracking-widest text-center">Gateway to Legacy</p>
                <div class="h-1 w-8 bg-primary"></div>
            </div>
            <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-4">
                Global Login
            </h2>
            <p class="text-gray-500 font-medium">
                Step into the elite circle of Nepal's global innovators.
            </p>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white/80 backdrop-blur-xl py-10 px-6 shadow-premium border border-gray-100 rounded-[2.5rem] sm:px-12">
                <form class="space-y-6" action="api/auth/login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2 ml-1">Email
                            Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                placeholder="your.name@global.com"
                                class="block w-full pl-11 pr-4 py-4 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm">
                        </div>
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password"
                            class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password" required placeholder="••••••••"
                                class="block w-full pl-11 pr-12 py-4 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition-colors focus:outline-none">
                                <svg class="h-5 w-5" x-show="!showPassword" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="h-5 w-5" x-show="showPassword" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox"
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded-lg cursor-pointer">
                            <label for="remember_me"
                                class="ml-2 block text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer">
                                Remember Me
                            </label>
                        </div>

                        <div class="text-xs">
                            <a href="forgot-password.php"
                                class="font-bold text-primary hover:text-red-700 uppercase tracking-wider transition-colors">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-4 px-6 border border-transparent rounded-2xl shadow-glow text-sm font-black text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transform active:scale-[0.98] transition-all duration-200">
                            Login
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>

                    <div class="text-center pt-4">
                        <p class="text-xs font-medium text-gray-500">
                            New to the Alliance? <a href="register.php"
                                class="text-primary font-bold hover:underline">Request Access</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>