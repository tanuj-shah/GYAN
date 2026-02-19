<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = "Our Programs";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen">
    <!-- Premium Hero Section -->
    <div class="relative bg-white pt-24 pb-16 md:pb-32 overflow-hidden">
        <!-- Decorative Framing Flags -->
        <div class="absolute inset-0 pointer-events-none hidden md:block select-none overflow-hidden"
            aria-hidden="true">
            <img src="img/flag.webp" alt=""
                class="absolute left-[-2rem] lg:left-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-40 object-contain"
                loading="lazy">
            <img src="img/flag.webp" alt=""
                class="absolute right-[-2rem] lg:right-0 top-1/2 -translate-y-1/2 w-32 lg:w-64 opacity-40 object-contain scale-x-[-1]"
                loading="lazy">
        </div>
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-white opacity-20">
            </div>
            <div class="absolute inset-x-0 bottom-0 h-24"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1
                class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl uppercase tracking-widest">
                Our Programs</h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-500">
                Empowering the next generation through education, innovation, and global collaboration.
            </p>
        </div>
    </div>

    <!-- Programs Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-16 relative z-20 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Program 1 -->
            <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group"
                data-aos="fade-up">
                <div class="h-64 bg-gray-200 relative overflow-hidden">
                    <img src="img/gyancommunity.webp" alt="Youth Leadership"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6">
                        <span
                            class="px-3 py-1 bg-primary text-white text-xs font-bold uppercase tracking-widest rounded-full">Leadership</span>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-primary transition-colors">Global
                        Youth Leadership</h3>
                    <p class="text-gray-500 mb-6 line-clamp-3">
                        A comprehensive program designed to nurture the next generation of Nepali leaders through
                        mentorship, workshops, and global exposure.
                    </p>
                    <a href="#"
                        class="inline-flex items-center text-primary font-bold uppercase tracking-wider hover:text-primary-dark transition-colors">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Program 2 -->
            <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group"
                data-aos="fade-up" data-aos-delay="100">
                <div class="h-64 bg-gray-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-blue-900 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="absolute bottom-6 left-6">
                        <span
                            class="px-3 py-1 bg-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded-full">Innovation</span>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-primary transition-colors">Tech
                        for Nepal</h3>
                    <p class="text-gray-500 mb-6 line-clamp-3">
                        Bridging the digital divide by connecting Nepali tech talent with global opportunities and
                        fostering local innovation.
                    </p>
                    <a href="#"
                        class="inline-flex items-center text-primary font-bold uppercase tracking-wider hover:text-primary-dark transition-colors">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Program 3 -->
            <div class="bg-white rounded-[2.5rem] shadow-premium border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group"
                data-aos="fade-up" data-aos-delay="200">
                <div class="h-64 bg-gray-200 relative overflow-hidden">
                    <div class="absolute inset-0 bg-green-900 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="absolute bottom-6 left-6">
                        <span
                            class="px-3 py-1 bg-green-600 text-white text-xs font-bold uppercase tracking-widest rounded-full">Community</span>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-primary transition-colors">
                        Community Connect</h3>
                    <p class="text-gray-500 mb-6 line-clamp-3">
                        Strengthening local communities through grassroots initiatives, cultural exchange, and
                        sustainable development projects.
                    </p>
                    <a href="#"
                        class="inline-flex items-center text-primary font-bold uppercase tracking-wider hover:text-primary-dark transition-colors">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>