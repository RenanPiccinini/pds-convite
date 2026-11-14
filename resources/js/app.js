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

// ================= CUSTOM BEAUTIFUL MODAL SYSTEM =================
window.showPdsModal = function({
    title = 'PDS Convite',
    message = '',
    type = 'success', // 'success', 'info', 'warning', 'error', 'copy'
    confirmText = 'OK',
    cancelText = null,
    onConfirm = null,
    onCancel = null
} = {}) {
    // Remove existing modal if any
    const existing = document.getElementById('pds-global-modal');
    if (existing) {
        existing.remove();
    }

    const backdrop = document.createElement('div');
    backdrop.id = 'pds-global-modal';
    backdrop.className = 'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md transition-opacity duration-300';
    backdrop.style.opacity = '0';

    let iconHtml = '';
    let glowColor = 'rgba(0, 240, 255, 0.4)';

    if (type === 'copy' || type === 'success') {
        glowColor = 'rgba(0, 240, 255, 0.4)';
        iconHtml = `
            <div class="h-16 w-16 mx-auto rounded-full bg-cyan-500/20 border-2 border-cyan-400 flex items-center justify-center text-cyan-400 shadow-[0_0_25px_rgba(0,240,255,0.5)]">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        `;
    } else if (type === 'warning' || type === 'delete') {
        glowColor = 'rgba(239, 68, 68, 0.4)';
        iconHtml = `
            <div class="h-16 w-16 mx-auto rounded-full bg-rose-500/20 border-2 border-rose-400 flex items-center justify-center text-rose-400 shadow-[0_0_25px_rgba(239,68,68,0.5)]">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        `;
    } else {
        glowColor = 'rgba(168, 85, 247, 0.4)';
        iconHtml = `
            <div class="h-16 w-16 mx-auto rounded-full bg-purple-500/20 border-2 border-purple-400 flex items-center justify-center text-purple-400 shadow-[0_0_25px_rgba(168,85,247,0.5)]">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        `;
    }

    const hasCancel = cancelText !== null;

    backdrop.innerHTML = `
        <div class="glass-panel w-full max-w-sm sm:max-w-md rounded-3xl p-6 sm:p-8 border border-cyan-500/30 shadow-[0_20px_50px_rgba(0,0,0,0.8),0_0_40px_${glowColor}] text-center space-y-5 transform transition-all duration-300 scale-95" style="background: rgba(10, 16, 38, 0.95);">
            ${iconHtml}
            <div class="space-y-2">
                <h3 class="text-xl font-black text-white font-display tracking-tight">${title}</h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">${message}</p>
            </div>
            <div class="pt-2 flex items-center justify-center gap-3">
                ${hasCancel ? `
                    <button id="pds-modal-cancel" type="button" class="flex-1 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 active:scale-[0.98] text-slate-300 font-semibold text-xs transition-colors border border-slate-700 touch-manipulation cursor-pointer select-none">
                        ${cancelText}
                    </button>
                ` : ''}
                <button id="pds-modal-confirm" type="button" class="${hasCancel ? 'flex-1' : 'w-full'} py-3.5 px-6 rounded-xl btn-pds-primary active:scale-[0.98] text-white font-bold text-xs tracking-wide shadow-lg touch-manipulation cursor-pointer select-none">
                    ${confirmText}
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(backdrop);

    // Animate in
    requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        const modalBox = backdrop.querySelector('.glass-panel');
        if (modalBox) {
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }
    });

    const closeModal = () => {
        backdrop.style.opacity = '0';
        const modalBox = backdrop.querySelector('.glass-panel');
        if (modalBox) {
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
        }
        setTimeout(() => backdrop.remove(), 250);
    };

    const confirmBtn = backdrop.querySelector('#pds-modal-confirm');
    confirmBtn.addEventListener('click', () => {
        closeModal();
        if (typeof onConfirm === 'function') onConfirm();
    });

    if (hasCancel) {
        const cancelBtn = backdrop.querySelector('#pds-modal-cancel');
        cancelBtn.addEventListener('click', () => {
            closeModal();
            if (typeof onCancel === 'function') onCancel();
        });
    }

    // Close on click backdrop
    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            closeModal();
            if (typeof onCancel === 'function') onCancel();
        }
    });
};

// Global helpers and override window.alert
window.pdsAlert = function(message, title = 'PDS Convite', type = 'info') {
    window.showPdsModal({
        title: title,
        message: message,
        type: type,
        confirmText: 'Entendido'
    });
};

window.pdsConfirm = function(message, onConfirm, title = 'Confirmação', confirmText = 'Sim, continuar', cancelText = 'Cancelar') {
    window.showPdsModal({
        title: title,
        message: message,
        type: 'warning',
        confirmText: confirmText,
        cancelText: cancelText,
        onConfirm: onConfirm
    });
};

// Intercept browser alert with beautiful custom modal
window.alert = function(msg) {
    let title = 'PDS Convite';
    let type = 'info';

    if (typeof msg === 'string') {
        if (msg.toLowerCase().includes('copiad')) {
            title = 'Link Copiado!';
            type = 'copy';
        } else if (msg.toLowerCase().includes('sucesso')) {
            title = 'Sucesso!';
            type = 'success';
        } else if (msg.toLowerCase().includes('erro') || msg.toLowerCase().includes('encerrad')) {
            title = 'Atenção';
            type = 'warning';
        }
    }

    window.showPdsModal({
        title: title,
        message: msg,
        type: type,
        confirmText: 'Entendido'
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
