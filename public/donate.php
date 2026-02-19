<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = "Donate";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="pt-24 pb-16 md:pb-32 bg-white relative overflow-hidden">
    <!-- Decorative Framing Flags -->
    <div class="absolute inset-0 pointer-events-none hidden md:block select-none overflow-hidden" aria-hidden="true">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="fade-up">
        <h1
            class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl uppercase tracking-widest">
            Empower Growth</h1>
        <div class="mt-4 flex items-center justify-center space-x-4">
            <div class="h-1 w-12 bg-primary"></div>
            <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Support Our Mission</p>
            <div class="h-1 w-12 bg-primary"></div>
        </div>
        <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
            Your contribution helps us provide the critical scholarships, mentorship, and resources needed to empower
            Nepali youth worldwide.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-12 md:pb-32">
    <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto text-primary mb-6">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
        </svg>
    </div>
    <h3 class="text-2xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Support GYAN via IRD</h3>
    <p class="text-gray-500 mb-8 leading-relaxed">While our direct gateway is under integration, we are
        accepting contributions through our mother company, <span class="font-bold text-primary">IRD
            Nepal</span>.</p>

    <a href="https://www.paypal.com/donate/?hosted_button_id=LKNRRNPS7Y8VL" target="_blank"
        class="inline-flex justify-center items-center px-10 py-4 border border-transparent text-lg font-black rounded-full text-white bg-primary hover:bg-blue-700 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
        Contribute through IRD
    </a>
</div>

<div class="flex flex-col items-center justify-center">
    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-4">Other Inquiries</p>
    <a href="contact.php"
        class="inline-flex justify-center items-center px-8 py-3 border-2 border-gray-100 text-sm font-bold rounded-full text-gray-500 hover:border-secondary hover:text-secondary transition-all">
        Contact for Partnership
    </a>
</div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>