/**
 * RazaToys Storefront Core JavaScript
 * Handles navigation, mobile drawer, dropdowns, hero carousel, product slider, and accessibility
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Category Mega Menu Dropdown
    const catBtn = document.getElementById('raza-cat-btn');
    const catDropdown = document.getElementById('raza-cat-dropdown');

    if (catBtn && catDropdown) {
        catBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (moreDropdown) moreDropdown.classList.remove('is-open');
            const isOpen = catDropdown.classList.toggle('is-open');
            catBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!catDropdown.contains(e.target) && !catBtn.contains(e.target)) {
                catDropdown.classList.remove('is-open');
                catBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 1b. Accessible More Dropdown
    const moreBtn = document.getElementById('raza-more-btn');
    const moreDropdown = document.getElementById('raza-more-dropdown');

    if (moreBtn && moreDropdown) {
        moreBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (catDropdown) catDropdown.classList.remove('is-open');
            const isOpen = moreDropdown.classList.toggle('is-open');
            moreBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!moreDropdown.contains(e.target) && !moreBtn.contains(e.target)) {
                moreDropdown.classList.remove('is-open');
                moreBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 2. Mobile Drawer Navigation
    const mobileToggle = document.getElementById('raza-mobile-toggle');
    const mobileDrawer = document.getElementById('raza-mobile-drawer');
    const mobileBackdrop = document.getElementById('raza-mobile-backdrop');
    const mobileClose = document.getElementById('raza-mobile-close');

    function openMobileDrawer() {
        if (mobileDrawer && mobileBackdrop) {
            mobileDrawer.classList.add('is-active');
            mobileBackdrop.classList.add('is-active');
            document.body.style.overflow = 'hidden';
            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'true');
        }
    }

    function closeMobileDrawer() {
        if (mobileDrawer && mobileBackdrop) {
            mobileDrawer.classList.remove('is-active');
            mobileBackdrop.classList.remove('is-active');
            document.body.style.overflow = '';
            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
        }
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', openMobileDrawer);
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileDrawer);
    }

    if (mobileBackdrop) {
        mobileBackdrop.addEventListener('click', closeMobileDrawer);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (mobileDrawer && mobileDrawer.classList.contains('is-active')) {
                closeMobileDrawer();
            }
            if (catDropdown && catDropdown.classList.contains('is-open')) {
                catDropdown.classList.remove('is-open');
                if (catBtn) catBtn.setAttribute('aria-expanded', 'false');
            }
            if (moreDropdown && moreDropdown.classList.contains('is-open')) {
                moreDropdown.classList.remove('is-open');
                if (moreBtn) moreBtn.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // 3. Hero Carousel Controller
    const carousel = document.getElementById('raza-hero-carousel');
    if (carousel) {
        const slides = carousel.querySelectorAll('.raza-carousel__slide');
        const dots = carousel.querySelectorAll('.raza-carousel__dot');
        const prevBtn = document.getElementById('raza-carousel-prev');
        const nextBtn = document.getElementById('raza-carousel-next');
        let currentSlide = 0;
        let slideInterval = null;

        function showSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            currentSlide = index;

            slides.forEach((slide, i) => {
                slide.classList.toggle('is-active', i === currentSlide);
            });

            dots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === currentSlide);
                dot.setAttribute('aria-selected', i === currentSlide ? 'true' : 'false');
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                showSlide(currentSlide - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                showSlide(currentSlide + 1);
            });
        }

        dots.forEach((dot, i) => {
            dot.addEventListener('click', function () {
                showSlide(i);
            });
        });

        function startAutoPlay() {
            if (!slideInterval && slides.length > 1) {
                slideInterval = setInterval(function () {
                    showSlide(currentSlide + 1);
                }, 5500);
            }
        }

        function stopAutoPlay() {
            if (slideInterval) {
                clearInterval(slideInterval);
                slideInterval = null;
            }
        }

        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);
        carousel.addEventListener('focusin', stopAutoPlay);
        carousel.addEventListener('focusout', startAutoPlay);

        // Keyboard arrow navigation
        carousel.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                showSlide(currentSlide - 1);
            } else if (e.key === 'ArrowRight') {
                showSlide(currentSlide + 1);
            }
        });

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        carousel.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoPlay();
        }, { passive: true });

        carousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 45) {
                showSlide(currentSlide + 1);
            } else if (touchEndX - touchStartX > 45) {
                showSlide(currentSlide - 1);
            }
            startAutoPlay();
        }, { passive: true });

        startAutoPlay();
    }

    // 4. Trending Tabs Toggle
    const tabBtns = document.querySelectorAll('.raza-tab-btn');
    if (tabBtns.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                tabBtns.forEach(b => {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-selected', 'false');
                });
                this.classList.add('is-active');
                this.setAttribute('aria-selected', 'true');
            });
        });
    }
});

// 5. Global Product Slider Scroll Helper
window.scrollProductSlider = function (sliderId, distance) {
    const slider = document.getElementById(sliderId);
    if (slider) {
        slider.scrollBy({ left: distance, behavior: 'smooth' });
    }
};
