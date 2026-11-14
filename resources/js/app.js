import Alpine from 'alpinejs';
import confetti from 'canvas-confetti';

window.confetti = confetti;

window.triggerConfetti = function() {
    // Cyber celebration confetti with PDS colors: cyan, purple, gold, white
    const count = 200;
    const defaults = {
        origin: { y: 0.7 }
    };

    function fire(particleRatio, opts) {
        confetti({
            ...defaults,
            ...opts,
            particleCount: Math.floor(count * particleRatio),
            colors: ['#00f0ff', '#a855f7', '#ec4899', '#ffffff', '#38bdf8', '#e2e8f0']
        });
    }

    fire(0.25, {
        spread: 26,
        startVelocity: 55,
    });
    fire(0.2, {
        spread: 60,
    });
    fire(0.35, {
        spread: 100,
        decay: 0.91,
        scalar: 0.8
    });
    fire(0.1, {
        spread: 120,
        startVelocity: 25,
        decay: 0.92,
        scalar: 1.2
    });
    fire(0.1, {
        spread: 120,
        startVelocity: 45,
    });
};

// 3D Parallax & Tilt Effect
document.addEventListener('DOMContentLoaded', () => {
    // Parallax mouse movement on background
    window.addEventListener('mousemove', (e) => {
        const mouseX = (e.clientX / window.innerWidth) - 0.5;
        const mouseY = (e.clientY / window.innerHeight) - 0.5;

        document.querySelectorAll('.parallax-layer-slow').forEach(el => {
            el.style.transform = `translate3d(${mouseX * -25}px, ${mouseY * -25}px, 0)`;
        });

        document.querySelectorAll('.parallax-layer-fast').forEach(el => {
            el.style.transform = `translate3d(${mouseX * 40}px, ${mouseY * 40}px, 0)`;
        });
    });

    // 3D Tilt on elements with data-tilt
    document.querySelectorAll('[data-tilt]').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * -7;
            const rotateY = ((x - centerX) / centerX) * 7;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            card.style.transition = 'transform 0.5s ease';
        });

        card.addEventListener('mouseenter', () => {
            card.style.transition = 'none';
        });
    });
});

window.Alpine = Alpine;
Alpine.start();
