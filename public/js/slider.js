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
                isTransitioning = true;
                currentIndex += 1;
                setPosition(currentIndex, true);
                syncDots();
            }

            function goPrev() {
                if (isTransitioning) return;
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
            });

            // Autoplay + pause on hover/focus
            let refreshSlider = null;

            function stopAutoSlider() {
                if (refreshSlider) {
                    clearInterval(refreshSlider);
                    refreshSlider = null;
                }
            }

            function startAutoSlider() {
                stopAutoSlider();
                refreshSlider = setInterval(() => { goNext(); }, 4000);
            }

            slider.addEventListener('mouseenter', stopAutoSlider);
            slider.addEventListener('mouseleave', startAutoSlider);
            slider.addEventListener('focusin', stopAutoSlider);
            slider.addEventListener('focusout', startAutoSlider);

            // Posiciona inicialmente no primeiro slide real (sem animação)
            // Fazemos isso após o layout para garantir offsetLeft correto.
            const init = () => {
                currentIndex = 1;
                setPosition(currentIndex, false);
                syncDots();
            };

            requestAnimationFrame(init);
            window.addEventListener('load', init, { once: true });
            window.addEventListener('resize', () => setPosition(currentIndex, false));

            startAutoSlider();
        }
    }
}