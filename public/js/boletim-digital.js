document.addEventListener('DOMContentLoaded', () => {
    const videos = Array.from(document.querySelectorAll('.boletim-feed video'));

    if (videos.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const video = entry.target;

                if (entry.isIntersecting) {
                    video.play().catch(() => {
                        // Alguns navegadores bloqueiam autoplay até a primeira interação.
                    });
                    return;
                }

                video.pause();
            });
        }, {
            threshold: 0.55,
        });

        videos.forEach((video) => {
            video.muted = true;
            observer.observe(video);
        });
    }

    const lightbox = document.getElementById('boletim-lightbox');
    const lightboxImg = document.getElementById('boletim-lightbox-img');
    const lightboxClose = document.querySelector('.boletim-lightbox__close');
    const lightboxTriggers = Array.from(document.querySelectorAll('.boletim-lightbox-trigger'));
    const pageAside = document.querySelector('aside');

    if (!lightbox || !lightboxImg || !lightboxClose || !lightboxTriggers.length) {
        return;
    }

    function syncLightboxBounds() {
        let rightGap = 0;

        if (pageAside) {
            const asideRect = pageAside.getBoundingClientRect();

            if (asideRect.width > 0 && asideRect.right > asideRect.left) {
                rightGap = Math.max(0, asideRect.width);
            }
        }

        lightbox.style.setProperty('--boletim-lightbox-right-gap', `${rightGap}px`);
    }

    function openLightbox(src, alt) {
        lightboxImg.src = src || '';
        lightboxImg.alt = alt || '';
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        syncLightboxBounds();
        lightbox.classList.add('active');
        lightbox.setAttribute('aria-hidden', 'false');
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImg.src = '';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }

    syncLightboxBounds();
    window.addEventListener('resize', syncLightboxBounds);

    lightboxTriggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const image = trigger.querySelector('img');
            openLightbox(trigger.dataset.full || image?.src, image?.alt || '');
        });
    });

    lightboxClose.addEventListener('click', closeLightbox);

    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && lightbox.classList.contains('active')) {
            closeLightbox();
        }
    });
});
