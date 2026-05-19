import gsap from 'gsap';

// Inisialisasi Splash Animation
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('track');
    const streamFill = document.getElementById('stream-fill');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const totalSlides = 3;
    
    let currentSlide = 0;
    let isAnimating = false;

    // 1. SETUP BUBBLES
    const bubblesContainer = document.getElementById('bubbles');
    for(let i = 0; i < 15; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        const size = Math.random() * 30 + 10;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        bubble.style.left = `${Math.random() * 90 + 5}%`;
        
        bubblesContainer.appendChild(bubble);

        // Animate bubbles naik
        gsap.to(bubble, {
            y: `-120vh`,
            x: `+=${Math.random() * 100 - 50}`,
            duration: Math.random() * 7 + 8,
            repeat: -1,
            delay: Math.random() * 5,
            ease: "sine.inOut"
        });
    }

    // 2. ANIMATE WAVES
    gsap.to('#wave1', { x: '-25%', duration: 18, repeat: -1, ease: 'sine.inOut' });
    gsap.to('#wave2', { x: '-15%', duration: 14, repeat: -1, ease: 'sine.inOut', delay: -3 });

    // 3. FLOATING ICON ANIMATION
    gsap.to('.icon-glass', {
        y: '+=8',
        duration: 3.5,
        yoyo: true,
        repeat: -1,
        ease: "sine.inOut",
        stagger: 0.2
    });

    // 4. SHINE EFFECT ON ICONS
    gsap.to('.icon-shine', {
        left: '150%',
        duration: 2,
        ease: "power2.inOut",
        repeat: -1,
        repeatDelay: 3,
        stagger: 0.3
    });

    // 5. FUNGSI NAVIGASI SLIDE (EXPOSE TO WINDOW)
    window.goToSlide = function(index) {
        if (isAnimating || index < 0 || index >= totalSlides || index === currentSlide) return;
        
        isAnimating = true;

        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });

        // Update stream progress
        gsap.to(streamFill, {
            width: `${((index + 1) / totalSlides) * 100}%`,
            duration: 0.6,
            ease: "power2.inOut"
        });

        // Geser slide
        gsap.to(track, {
            xPercent: -index * (100 / totalSlides),
            duration: 0.8,
            ease: "power3.inOut",
            onComplete: () => {
                isAnimating = false;
            }
        });

        // Animasi konten slide baru
        const newSlideContent = slides[index].querySelectorAll('.slide-inner > *');
        gsap.fromTo(newSlideContent, 
            { y: 30, opacity: 0, scale: 0.95 },
            { 
                y: 0, 
                opacity: 1, 
                scale: 1, 
                duration: 0.6, 
                stagger: 0.1, 
                ease: "back.out(1.2)",
                overwrite: "auto"
            }
        );

        currentSlide = index;
    };

    // 6. SWIPE DETECTION
    let startX = 0;
    const container = document.getElementById('app-container');

    container.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    }, { passive: true });

    container.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;

        if (Math.abs(diff) > 50) {
            if (diff > 0 && currentSlide < totalSlides - 1) {
                window.goToSlide(currentSlide + 1);
            } else if (diff < 0 && currentSlide > 0) {
                window.goToSlide(currentSlide - 1);
            }
        }
    });

    // Trigger animasi awal untuk slide 0
    setTimeout(() => {
        const firstSlideContent = slides[0].querySelectorAll('.slide-inner > *');
        gsap.fromTo(firstSlideContent,
            { y: 30, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "power2.out" }
        );
    }, 100);
});