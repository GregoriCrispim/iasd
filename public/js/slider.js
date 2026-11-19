const slider = document.querySelector('.slider');
if (!slider) {
    // Este script é carregado em algumas páginas que não possuem carrossel.
} else {
    const list = slider.querySelector('.list');
    const prev = slider.querySelector('#prev');
    const next = slider.querySelector('#next');
    const dots = slider.querySelectorAll('.dots li');

    if (!list || !prev || !next) {
        // Estrutura incompleta: não inicializa.
    } else {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const SLIDE_DWELL_MS = 4000;
        const CLICK_HINT_MS = 2900;
        const TRANSITION_MS = 1000;
        const TRANSITION_FALLBACK_MS = TRANSITION_MS + 150;

        const originalItems = Array.from(list.querySelectorAll('.item'));
        const originalCount = originalItems.length;

        if (originalCount <= 1) {
            // Com 0/1 slide não faz sentido animar/infinito.
        } else {
            const firstClone = originalItems[0].cloneNode(true);
            const lastClone = originalItems[originalCount - 1].cloneNode(true);
            firstClone.classList.add('clone');
            lastClone.classList.add('clone');
            list.appendChild(firstClone);
            list.insertBefore(lastClone, originalItems[0]);

            // 0 = clone do último | 1..N = reais | N+1 = clone do primeiro
            let currentIndex = 1;
            let clickHintTimer = null;
            let clickPressTimers = [];
            let refreshSlider = null;
            let settleTimer = null;
            let autoplayPausedByUser = false;
            let isTransitioning = false;
            let pendingSettle = false;
            let initialized = false;

            function setTransitionEnabled(enabled) {
                list.style.transition = enabled ? `${TRANSITION_MS}ms` : 'none';
            }

            function getAllItems() {
                return Array.from(list.querySelectorAll('.item'));
            }

            function syncSlideWidths() {
                const slideWidth = slider.clientWidth;
                if (!slideWidth) return;
                slider.style.setProperty('--slide-width', `${slideWidth}px`);
            }

            function setPosition(index, animate = true) {
                const all = getAllItems();
                const target = all[index];
                if (!target) return;

                setTransitionEnabled(animate);
                list.style.left = -target.offsetLeft + 'px';
            }

            function setActiveDot(dotIndex) {
                if (!dots || dots.length === 0) return;

                const lastActive = slider.querySelector('.dots li.active');
                if (lastActive) lastActive.classList.remove('active');

                const nextActive = dots[dotIndex];
                if (nextActive) nextActive.classList.add('active');
            }

            function dotIndexFromCurrent() {
                let idx = currentIndex - 1;
                if (idx < 0) idx = originalCount - 1;
                if (idx >= originalCount) idx = 0;
                return idx;
            }

            function syncDots() {
                setActiveDot(dotIndexFromCurrent());
            }

            function clearClickHintTimers() {
                if (clickHintTimer) {
                    clearTimeout(clickHintTimer);
                    clickHintTimer = null;
                }
                clickPressTimers.forEach((timer) => clearTimeout(timer));
                clickPressTimers = [];
            }

            function stopAutoSlider() {
                if (refreshSlider) {
                    clearTimeout(refreshSlider);
                    refreshSlider = null;
                }
            }

            function armAutoplay(extraDelay = 0) {
                stopAutoSlider();
                if (autoplayPausedByUser || document.hidden) return;

                refreshSlider = setTimeout(() => {
                    goNext();
                }, SLIDE_DWELL_MS + extraDelay);
            }

            function playClickHint() {
                clearClickHintTimers();

                getAllItems().forEach((item) => {
                    const overlay = item.querySelector('.carousel-click-overlay');
                    const link = item.querySelector('.carousel-cta-link');
                    if (overlay) overlay.classList.remove('carousel-click-overlay--play');
                    if (link) link.classList.remove('carousel-cta-link--press');
                });

                const current = getAllItems()[currentIndex];
                if (!current) return 0;

                const overlay = current.querySelector('.carousel-click-overlay');
                const link = current.querySelector('.carousel-cta-link');
                if (!overlay || !link) return 0;

                if (prefersReducedMotion) {
                    overlay.classList.add('carousel-click-overlay--play');
                    clickHintTimer = setTimeout(() => {
                        overlay.classList.remove('carousel-click-overlay--play');
                    }, 1200);
                    return 1200;
                }

                void overlay.offsetWidth;
                overlay.classList.add('carousel-click-overlay--play');

                [320, 800, 1770, 2250].forEach((delay) => {
                    const pressOn = setTimeout(() => {
                        link.classList.add('carousel-cta-link--press');
                    }, delay);
                    const pressOff = setTimeout(() => {
                        link.classList.remove('carousel-cta-link--press');
                    }, delay + 160);
                    clickPressTimers.push(pressOn, pressOff);
                });

                clickHintTimer = setTimeout(() => {
                    overlay.classList.remove('carousel-click-overlay--play');
                    link.classList.remove('carousel-cta-link--press');
                }, CLICK_HINT_MS);

                return CLICK_HINT_MS;
            }

            function onSlideSettled() {
                const hintMs = playClickHint();
                armAutoplay(hintMs);
            }

            function jumpWithoutAnimation(index) {
                setTransitionEnabled(false);
                setPosition(index, false);
                void list.offsetHeight;
                setTransitionEnabled(true);
            }

            function wrapCloneIndex() {
                if (currentIndex === 0) {
                    currentIndex = originalCount;
                    jumpWithoutAnimation(currentIndex);
                } else if (currentIndex === originalCount + 1) {
                    currentIndex = 1;
                    jumpWithoutAnimation(currentIndex);
                }
            }

            function finishTransition() {
                if (!pendingSettle) return;
                pendingSettle = false;

                if (settleTimer) {
                    clearTimeout(settleTimer);
                    settleTimer = null;
                }

                wrapCloneIndex();
                isTransitioning = false;
                syncDots();
                onSlideSettled();
            }

            function beginTransition() {
                isTransitioning = true;
                pendingSettle = true;

                if (settleTimer) clearTimeout(settleTimer);
                // Se transitionend falhar (aba em background, resize, etc.), não trava.
                settleTimer = setTimeout(finishTransition, TRANSITION_FALLBACK_MS);
            }

            function goNext() {
                if (isTransitioning) return;
                stopAutoSlider();
                clearClickHintTimers();
                beginTransition();
                currentIndex += 1;
                setPosition(currentIndex, true);
                syncDots();
            }

            function goPrev() {
                if (isTransitioning) return;
                stopAutoSlider();
                clearClickHintTimers();
                beginTransition();
                currentIndex -= 1;
                setPosition(currentIndex, true);
                syncDots();
            }

            next.onclick = goNext;
            prev.onclick = goPrev;

            dots.forEach((li, key) => {
                li.addEventListener('click', function () {
                    if (isTransitioning) return;
                    const targetIndex = key + 1;
                    if (targetIndex === currentIndex) return;

                    stopAutoSlider();
                    clearClickHintTimers();
                    beginTransition();
                    currentIndex = targetIndex;
                    setPosition(currentIndex, true);
                    syncDots();
                });
            });

            list.addEventListener('transitionend', (e) => {
                if (e.target !== list) return;
                if (e.propertyName !== 'left') return;
                finishTransition();
            });

            list.addEventListener('transitioncancel', (e) => {
                if (e.target !== list) return;
                if (e.propertyName && e.propertyName !== 'left') return;
                finishTransition();
            });

            slider.addEventListener('mouseenter', () => {
                autoplayPausedByUser = true;
                stopAutoSlider();
            });
            slider.addEventListener('mouseleave', () => {
                autoplayPausedByUser = false;
                if (isTransitioning) return;
                const overlayPlaying = getAllItems()[currentIndex]?.querySelector('.carousel-click-overlay--play');
                armAutoplay(overlayPlaying ? CLICK_HINT_MS : 0);
            });
            slider.addEventListener('focusin', () => {
                autoplayPausedByUser = true;
                stopAutoSlider();
            });
            slider.addEventListener('focusout', () => {
                autoplayPausedByUser = false;
                if (isTransitioning) return;
                const overlayPlaying = getAllItems()[currentIndex]?.querySelector('.carousel-click-overlay--play');
                armAutoplay(overlayPlaying ? CLICK_HINT_MS : 0);
            });

            function syncLayoutOnly() {
                syncSlideWidths();
                // Interrompe transição em andamento sem resetar para o slide 1
                if (isTransitioning || pendingSettle) {
                    pendingSettle = false;
                    if (settleTimer) {
                        clearTimeout(settleTimer);
                        settleTimer = null;
                    }
                    wrapCloneIndex();
                    isTransitioning = false;
                    setPosition(currentIndex, false);
                    syncDots();
                    onSlideSettled();
                    return;
                }
                setPosition(currentIndex, false);
            }

            function initOnce() {
                if (initialized) return;
                initialized = true;
                syncSlideWidths();
                currentIndex = 1;
                setPosition(currentIndex, false);
                syncDots();
                onSlideSettled();
            }

            requestAnimationFrame(initOnce);
            window.addEventListener('load', syncLayoutOnly, { once: true });
            window.addEventListener('resize', syncLayoutOnly);

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stopAutoSlider();
                    return;
                }
                syncLayoutOnly();
            });
        }
    }
}
