<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = "Initiate Your Legacy";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen">
    <!-- Premium High-Class Hero (Synced with About) -->
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
                Initiate Your Legacy</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Member Journey</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                Step into a curated ecosystem of Nepal's most influential innovators and professional leaders. Your
                journey starts here.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-12 md:pb-32">
        <!-- Immersive Why Join Story (Synced with About Structure) -->
        <div
            class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-premium overflow-hidden border border-gray-100 mb-12 md:mb-20">
            <div class="lg:grid lg:grid-cols-2">
                <div class="p-6 md:p-20 flex flex-col justify-center" data-aos="fade-right">
                    <span class="text-primary font-black uppercase tracking-widest text-xs mb-6 block">The
                        Advantage</span>
                    <h2 class="text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter leading-none">Why Join
                        the Global Alliance?</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-8 italic">
                        "The Global Youth Alliance is not just a community; it is a movement dedicated to bridging our
                        roots with our global professional potential."
                    </p>
                    <p class="text-gray-500 text-lg leading-relaxed">
                        By connecting with GYAN, you gain direct access to a network of 1,200+ professionals across 45
                        countries. We provide the mentorship, resources, and influence needed to drive both your career
                        and Nepal's systemic progress forward.
                    </p>
                </div>
                <div class="relative min-h-[400px] lg:min-h-full p-8 md:p-10 flex items-center" data-aos="fade-left">
                    <!-- Registration Form Moved Here -->
                    <div class="w-full">
                        <div class="mb-8">
                            <span class="text-primary font-bold uppercase tracking-wider text-xs mb-2 block">Join
                                GYAN</span>
                            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Create Your Account</h2>
                            <p class="text-gray-600 text-sm">Already a member? <a href="login.php"
                                    class="text-primary font-semibold hover:underline">Sign in here</a></p>
                        </div>

                        <form class="space-y-6" action="api/auth/register.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" type="text" autocomplete="name" required
                                    placeholder="Enter your full name"
                                    class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-gray-900 placeholder-gray-400">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    placeholder="your.email@example.com"
                                    class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-gray-900 placeholder-gray-400">
                                <p class="text-xs text-gray-500 mt-1">One account per email address allowed</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password" name="password" type="password" required
                                    placeholder="Create a strong password"
                                    class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-gray-900 placeholder-gray-400">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters recommended</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirm" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirm" name="password_confirm" type="password" required
                                    placeholder="Confirm your password"
                                    class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-gray-900 placeholder-gray-400">
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full flex justify-center items-center gap-2 py-3.5 px-6 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Create My Account
                                </button>
                            </div>

                            <div class="pt-2 text-center">
                                <p class="text-xs text-gray-500">By signing up, you agree to our <a href="#"
                                        class="text-primary hover:underline">Terms</a> and <a href="#"
                                        class="text-primary hover:underline">Privacy Policy</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrated GYAN Philosophy Cards (Synced with Home) -->
        <div class="grid md:grid-cols-3 gap-10 mb-20 relative z-10">
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                data-aos="fade-right">
                <div
                    class="w-16 h-16 bg-white border border-red-100 text-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Connect</h3>
                <p class="text-gray-500 leading-relaxed">Bridging the gap between global Nepali youth to foster
                    collaboration, share resources, and build a powerful, united network.</p>
            </div>
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                data-aos="fade-up">
                <div
                    class="w-16 h-16 bg-white border border-blue-100 text-secondary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-secondary group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Inspire</h3>
                <p class="text-gray-500 leading-relaxed">Empowering the next generation through visionary mentorship
                    and professional guidance from established global leaders.</p>
            </div>
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-2xl transition-all duration-500 group"
                data-aos="fade-left">
                <div
                    class="w-16 h-16 bg-white border border-green-100 text-accent rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18L9 11.25l4.306 4.307L20.25 7.5M20.25 7.5H15.75M20.25 7.5v4.5" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Impact</h3>
                <p class="text-gray-500 leading-relaxed">Driving tangible national development through innovative
                    projects, community partnership, and collective youth action.</p>
            </div>
        </div>

        <!-- Polished Footnote (Synced with About Partner Cloud Style) -->
        <div class="mt-16 md:mt-32 bg-gray-100/50 backdrop-blur-md rounded-[2rem] md:rounded-[3rem] p-8 md:p-24 border border-gray-100 text-center"
            data-aos="fade-up">
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-widest mb-12">Trusted Strategic Alliances
            </h2>
            <div
                class="flex flex-wrap justify-center items-center gap-12 md:gap-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                <span class="text-3xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">IRD
                    Nepal</span>
                <span class="text-3xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">Youth
                    Council</span>
                <span class="text-3xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">National
                    Hub</span>
                <span class="text-3xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">Gov
                    Relations</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 1000, once: true, easing: 'ease-out-quint' });
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>