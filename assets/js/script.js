// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    offset: 100
});

// Mobile Menu
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileNav = document.getElementById('mobileNav');
const mobileNavClose = document.getElementById('mobileNavClose');

if (mobileMenuBtn && mobileNav && mobileNavClose) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileNav.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    mobileNavClose.addEventListener('click', () => {
        mobileNav.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    document.addEventListener('click', (e) => {
        if (mobileNav.classList.contains('active') && !mobileNav.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mobileNav.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
}

// ========== HERO SLIDER (tanpa dot) ==========
const heroSlides = document.querySelectorAll('.hero .slide');
let heroCurrent = 0;
let heroInterval;
const HERO_INTERVAL = 4000;

function showHeroSlide(index) {
    if (index < 0) index = heroSlides.length - 1;
    if (index >= heroSlides.length) index = 0;
    heroSlides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
    heroCurrent = index;
}

function nextHeroSlide() {
    showHeroSlide(heroCurrent + 1);
}

function startHeroSlider() {
    if (heroInterval) clearInterval(heroInterval);
    heroInterval = setInterval(nextHeroSlide, HERO_INTERVAL);
}

function stopHeroSlider() {
    if (heroInterval) {
        clearInterval(heroInterval);
        heroInterval = null;
    }
}

const heroSection = document.querySelector('.hero');
if (heroSection && heroSlides.length > 0) {
    heroSection.addEventListener('mouseenter', stopHeroSlider);
    heroSection.addEventListener('mouseleave', startHeroSlider);
    startHeroSlider();
}

// ========== TESTIMONIAL SLIDER ==========
const testimonialSlides = document.querySelectorAll('.testimonial-slide');
const testimonialDots = document.querySelectorAll('.testimonial-dots .dot');

if (testimonialSlides.length > 0 && testimonialDots.length > 0) {
    let testimonialIndex = 1;
    function showTestimonial(n) {
        if (n > testimonialSlides.length) testimonialIndex = 1;
        if (n < 1) testimonialIndex = testimonialSlides.length;
        testimonialSlides.forEach(slide => slide.classList.remove('active'));
        testimonialDots.forEach(dot => dot.classList.remove('active'));
        testimonialSlides[testimonialIndex - 1].classList.add('active');
        testimonialDots[testimonialIndex - 1].classList.add('active');
    }
    testimonialDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            testimonialIndex = index + 1;
            showTestimonial(testimonialIndex);
        });
    });
    setInterval(() => {
        testimonialIndex++;
        if (testimonialIndex > testimonialSlides.length) testimonialIndex = 1;
        showTestimonial(testimonialIndex);
    }, 7000);
    showTestimonial(1);
}

// ========== SMOOTH SCROLL ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            window.scrollTo({
                top: target.offsetTop - 80,
                behavior: 'smooth'
            });
            if (mobileNav && mobileNav.classList.contains('active')) {
                mobileNav.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    });
});

// ========== ACTIVE NAV LINK ON SCROLL ==========
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (window.scrollY >= sectionTop - 150) {
            current = section.getAttribute('id');
        }
    });
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});