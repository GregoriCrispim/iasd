const slider = document.querySelector('.slider');
if (!slider) {
    // Este script é carregado em algumas páginas que não possuem carrossel.
    // Se não existir `.slider`, não fazemos nada.
} else {
    const list = slider.querySelector('.list');
    const prev = slider.querySelector('#prev');
    const next = slider.querySelector('#next');
    const dots = slider.querySelectorAll('.dots li');

    if (!list || !prev || !next) {
        // Estrutura incompleta: não inicializa.
    } else {
        const baseTransition = getComputedStyle(list).transition || '1s';
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const SLIDE_DWELL_MS = 4000;
        const CLICK_HINT_MS = 2900;

        // Itens originais (antes dos clones)
        const originalItems = Array.from(list.querySelectorAll('.item'));
        const originalCount = originalItems.length;

        if (originalCount <= 1) {
            // Com 0/1 slide não faz sentido animar/infinito.
        } else {
            // Clones para efeito infinito
            const firstClone = originalItems[0].cloneNode(true);
            const lastClone = originalItems[originalCount - 1].cloneNode(true);
            firstClone.classList.add('clone');
            lastClone.classList.add('clone');
            list.appendChild(firstClone);
            list.insertBefore(lastClone, originalItems[0]);

            // Índice "real" dentro da lista com clones:
            // 0 = clone do último
            // 1..originalCount = slides reais
            // originalCount+1 = clone do primeiro
            let currentIndex = 1;
            let clickHintTimer = null;
            let clickPressTimers = [];
            let refreshSlider = null;
            let autoplayPausedByUser = false;

            function setTransitionEnabled(enabled) {
                list.style.transition = enabled ? baseTransition : 'none';
            }

            function getAllItems() {
                return Array.from(list.querySelectorAll('.item'));
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
                // currentIndex 1..originalCount -> 0..originalCount-1
                // clone final (originalCount+1) -> 0
                // clone inicial (0) -> originalCount-1
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
                if (autoplayPausedByUser) return;

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

                // Sync a short "press" on the card with each tap of the hand
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
                // O tempo da mãozinha NÃO consome o dwell do slide.
                armAutoplay(hintMs);
            }

            function jumpWithoutAnimation(index) {
                setTransitionEnabled(false);
                setPosition(index, false);
                // força reflow para garantir que a próxima transição seja aplicada
                void list.offsetHeight;
                setTransitionEnabled(true);
            }

            let isTransitioning = false;

            function goNext() {
                if (isTransitioning) return;
                stopAutoSlider();
                clearClickHintTimers();
                isTransitioning = true;
                currentIndex += 1;
                setPosition(currentIndex, true);
                syncDots();
            }

            function goPrev() {
                if (isTransitioning) return;
                stopAutoSlider();
                clearClickHintTimers();
                isTransitioning = true;
                currentIndex -= 1;
                setPosition(currentIndex, true);
                syncDots();
            }

            next.onclick = goNext;
            prev.onclick = goPrev;

            // Clique nos dots (vai para slide real)
            dots.forEach((li, key) => {
                li.addEventListener('click', function () {
                    if (isTransitioning) return;
                    stopAutoSlider();
                    clearClickHintTimers();
                    isTransitioning = true;
                    currentIndex = key + 1;
                    setPosition(currentIndex, true);
                    syncDots();
                });
            });

            // Corrige o "teleporte" após a transição para manter o infinito
            list.addEventListener('transitionend', (e) => {
                // Ignora disparos de outras propriedades CSS (ex: opacity)
                if (e.propertyName !== 'left') return;

                if (currentIndex === 0) {
                    currentIndex = originalCount;
                    jumpWithoutAnimation(currentIndex);
                } else if (currentIndex === originalCount + 1) {
                    currentIndex = 1;
                    jumpWithoutAnimation(currentIndex);
                }

                isTransitioning = false;
                onSlideSettled();
            });

            slider.addEventListener('mouseenter', () => {
                autoplayPausedByUser = true;
                stopAutoSlider();
            });
            slider.addEventListener('mouseleave', () => {
                autoplayPausedByUser = false;
                const overlayPlaying = getAllItems()[currentIndex]?.querySelector('.carousel-click-overlay--play');
                armAutoplay(overlayPlaying ? CLICK_HINT_MS : 0);
            });
            slider.addEventListener('focusin', () => {
                autoplayPausedByUser = true;
                stopAutoSlider();
            });
            slider.addEventListener('focusout', () => {
                autoplayPausedByUser = false;
                const overlayPlaying = getAllItems()[currentIndex]?.querySelector('.carousel-click-overlay--play');
                armAutoplay(overlayPlaying ? CLICK_HINT_MS : 0);
            });

            // Posiciona inicialmente no primeiro slide real (sem animação)
            // Fazemos isso após o layout para garantir offsetLeft correto.
            const init = () => {
                currentIndex = 1;
                setPosition(currentIndex, false);
                syncDots();
                onSlideSettled();
            };

            requestAnimationFrame(init);
            window.addEventListener('load', init, { once: true });
            window.addEventListener('resize', () => setPosition(currentIndex, false));
        }
    }
}
