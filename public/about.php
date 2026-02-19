<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = "Our Legacy & Vision";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen">
    <!-- Premium High-Class Hero -->
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
                About GYAN</h1>
            <div class="mt-4 flex items-center justify-center space-x-4">
                <div class="h-1 w-12 bg-primary"></div>
                <p class="text-lg font-medium text-primary/80 uppercase tracking-widest">Our Journey & Impact</p>
                <div class="h-1 w-12 bg-primary"></div>
            </div>
            <p class="mt-8 max-w-2xl mx-auto text-xl text-gray-500 leading-relaxed">
                The Global Youth Alliance for Nepal is a movement dedicated to bridging the distance between our roots
                and our global potential.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 md:-mt-20 relative z-20 pb-16 md:pb-32">
        <!-- Immersive Brand Story -->
        <div class="bg-white rounded-[3rem] shadow-premium overflow-hidden border border-gray-100 mb-20">
            <div class="lg:grid lg:grid-cols-2">
                <div class="p-8 md:p-20 flex flex-col justify-center" data-aos="fade-right">
                    <span class="text-primary font-black uppercase tracking-widest text-xs mb-6 block">Our
                        Genesis</span>
                    <h2 class="text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter leading-none">Uniting
                        Youth for a Better Future</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-8 italic">
                        "The Global Youth Alliance for Nepal (GYAN) is a non-political, non-profit organization
                        dedicated to bringing together Nepali youth from around the world."
                    </p>
                    <p class="text-gray-500 text-lg leading-relaxed">
                        We believe in the power of collaboration, innovation, and unity to drive positive change in
                        Nepal and beyond. By connecting young professionals, students, and changemakers, we foster an
                        ecosystem of growth and support.
                    </p>
                </div>
                <div class="relative min-h-[400px] lg:min-h-full overflow-hidden" data-aos="fade-left">
                    <img class="absolute inset-0 w-full h-full object-cover" src="img/gyancommunity.webp"
                        alt="GYAN Community">
                    <div class="absolute inset-0"></div>
                </div>
            </div>
        </div>



        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-primary font-black uppercase tracking-widest text-2xl mb-4 block">Our Principles</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-32">
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-xl transition-all duration-500 group"
                data-aos="fade-up" data-aos-delay="0">
                <div class="text-primary mb-6 group-hover:scale-110 transition-transform duration-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <h4 class="text-xl font-black text-gray-900 mb-4 uppercase tracking-wider">Unity</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Standing together with one vision to support Nepal's
                    future growth and global presence.</p>
            </div>
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-xl transition-all duration-500 group"
                data-aos="fade-up" data-aos-delay="100">
                <div class="text-primary mb-6 group-hover:scale-110 transition-transform duration-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-black text-gray-900 mb-4 uppercase tracking-wider">Inclusive Participation</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Empowering every voice through democratic engagement,
                    ensuring diverse perspectives shape our collective future.</p>
            </div>
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border-[3px] border-[#8B0000] hover:shadow-xl transition-all duration-500 group"
                data-aos="fade-up" data-aos-delay="200">
                <div class="text-primary mb-6 group-hover:scale-110 transition-transform duration-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-black text-gray-900 mb-4 uppercase tracking-wider">Service</h4>
                <p class="text-gray-500 text-sm leading-relaxed">Dedicated to selfless contribution and giving back to
                    the community that nurtured our dreams.</p>
            </div>
        </div>

        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-primary font-black uppercase tracking-widest text-2xl mb-4 block">Leadership</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-32">
            <div class="group" data-aos="fade-up" data-aos-delay="0">
                <div
                    class="relative rounded-[2.5rem] overflow-hidden mb-8 h-[400px] shadow-premium group-hover:shadow-2xl transition-all duration-500">
                    <img class="w-full h-full object-cover grayscale-[40%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                        src="img/aarav.PNG" alt="Aarav Sharma">
                    <div class="absolute inset-0 bg-black/40">
                    </div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <p class="text-white font-black text-2xl uppercase tracking-tighter mb-1">Aarav Sharma</p>
                        <p class="text-primary font-bold text-xs uppercase tracking-[0.2em]">Global President</p>
                    </div>
                </div>
            </div>
            <div class="group" data-aos="fade-up" data-aos-delay="100">
                <div
                    class="relative rounded-[2.5rem] overflow-hidden mb-8 h-[400px] shadow-premium group-hover:shadow-2xl transition-all duration-500">
                    <img class="w-full h-full object-cover grayscale-[40%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                        src="img/priya.PNG" alt="Priya Karki">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60">
                    </div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <p class="text-white font-black text-2xl uppercase tracking-tighter mb-1">Priya Karki</p>
                        <p class="text-primary font-bold text-xs uppercase tracking-[0.2em]">Vice President</p>
                    </div>
                </div>
            </div>
            <div class="group" data-aos="fade-up" data-aos-delay="200">
                <div
                    class="relative rounded-[2.5rem] overflow-hidden mb-8 h-[400px] shadow-premium group-hover:shadow-2xl transition-all duration-500">
                    <img class="w-full h-full object-cover grayscale-[40%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                        src="img/bibek.PNG" alt="Bibek Thapa">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60">
                    </div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <p class="text-white font-black text-2xl uppercase tracking-tighter mb-1">Bibek Thapa</p>
                        <p class="text-primary font-bold text-xs uppercase tracking-[0.2em]">General Secretary</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Polished Partner Cloud -->
        <div class="bg-gray-100/50 backdrop-blur-md rounded-[3rem] p-8 md:p-24 border border-gray-100"
            data-aos="fade-up">
            <h2 class="text-primary font-black uppercase text-center tracking-widest text-2xl mb-4 block">Our Partners
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

<!-- AOS Library Initialization is handled in header/footer, ensuring local trigger -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 1000, once: true, easing: 'ease-out-quint' });
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>