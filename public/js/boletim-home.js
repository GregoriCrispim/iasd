(function () {
    const section = document.querySelector('[data-boletim-home]');
    if (!section || !section.classList.contains('boletim-home--intro')) {
        return;
    }

    const HOLD_MS = 3000;
    const TRANSITION_MS = 1400;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const settle = () => {
        section.classList.remove('boletim-home--intro', 'boletim-home--transitioning');
        section.classList.add('boletim-home--settled');
        section.removeAttribute('data-boletim-home');
    };

    if (reduceMotion) {
        settle();
        return;
    }

    let started = false;
    let settled = false;

    const finish = () => {
        if (settled) {
            return;
        }
        settled = true;
        settle();
    };

    const beginTransition = () => {
        section.classList.add('boletim-home--transitioning');
        section.classList.remove('boletim-home--intro');

        window.setTimeout(finish, TRANSITION_MS);

        const intro = section.querySelector('.boletim-home__intro');
        if (intro) {
            intro.addEventListener('transitionend', (event) => {
                if (event.target === intro && (event.propertyName === 'opacity' || event.propertyName === 'transform')) {
                    finish();
                }
            });
        }
    };

    const startHold = () => {
        if (started) {
            return;
        }
        started = true;
        window.setTimeout(beginTransition, HOLD_MS);
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                const visible = entries.some((entry) => entry.isIntersecting && entry.intersectionRatio >= 0.28);
                if (!visible) {
                    return;
                }
                observer.disconnect();
                startHold();
            },
            { threshold: [0, 0.28, 0.5] }
        );
        observer.observe(section);
    } else {
        startHold();
    }
})();
