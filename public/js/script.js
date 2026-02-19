document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            once: true,
            offset: 50,
            duration: 1200,
            easing: 'ease-out-cubic',
        });
    }



    // 3. Hero Swiper (Cinematic Fade)
    if (document.querySelector('.hero-swiper') && typeof Swiper !== 'undefined') {
        new Swiper('.hero-swiper', {
            loop: true,
            effect: 'fade',
            speed: 2000,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + ' bg-white opacity-50 hover:opacity-100 transition-opacity"></span>';
                },
            },
            allowTouchMove: false
        });
    }



    // 5. Canvas Fog Animation - DISABLED FOR PERFORMANCE
    // This animation is beautiful but causes scroll lag even with optimization
    // To re-enable, add data-enable="true" to canvas element
    const canvas = document.getElementById('fog-overlay');
    if (canvas && canvas.dataset.enable === 'true') {
        // Animation code here - currently disabled for performance
        console.log('Canvas animation disabled for optimal scroll performance');
    }

    // 6. Header Logic (Sticky Only)

    const topBar = document.getElementById('top-bar');
    const mainNav = document.getElementById('main-nav');

    if (topBar && mainNav) {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (window.scrollY > 10) {
                        mainNav.classList.add('shadow-[0_4px_16px_rgba(0,0,0,0.1)]');
                        mainNav.classList.remove('shadow-[0_2px_8px_rgba(0,0,0,0.05)]');
                    } else {
                        mainNav.classList.remove('shadow-[0_4px_16px_rgba(0,0,0,0.1)]');
                        mainNav.classList.add('shadow-[0_2px_8px_rgba(0,0,0,0.05)]');
                    }
                    ticking = false;
                });

                ticking = true;
            }
        });
    }

});
