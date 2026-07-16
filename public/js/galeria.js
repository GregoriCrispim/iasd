document.addEventListener('DOMContentLoaded', () => {
    initGaleriaCarrossel();
    initGaleriaFiltro();
    initGaleriaEventoPage();
});

/**
 * Página de listagem: carrossel de fotos recentes.
 */
function initGaleriaCarrossel() {
    const root = document.getElementById('galeriaCarrossel');
    if (!root) return;

    const slides = Array.from(root.querySelectorAll('.galeria-carrossel-slide'));
    const dots = Array.from(root.querySelectorAll('.galeria-carrossel-dot'));
    const prevBtn = document.getElementById('galeriaCarrosselPrev');
    const nextBtn = document.getElementById('galeriaCarrosselNext');
    if (slides.length === 0) return;

    const AUTOPLAY_MS = 5000;
    let current = 0;
    let timer = null;

    const show = (index) => {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => slide.classList.toggle('is-active', i === current));
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
    };

    const next = () => show(current + 1);
    const prev = () => show(current - 1);

    const stopAutoplay = () => {
        if (timer) clearInterval(timer);
        timer = null;
    };

    const startAutoplay = () => {
        stopAutoplay();
        if (slides.length > 1) timer = setInterval(next, AUTOPLAY_MS);
    };

    prevBtn?.addEventListener('click', (e) => { e.preventDefault(); prev(); startAutoplay(); });
    nextBtn?.addEventListener('click', (e) => { e.preventDefault(); next(); startAutoplay(); });
    dots.forEach((dot, i) => dot.addEventListener('click', (e) => { e.preventDefault(); show(i); startAutoplay(); }));

    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);

    show(0);
    startAutoplay();
}

/**
 * Página de listagem: busca por nome, filtro por mês e ordenação.
 */
function initGaleriaFiltro() {
    const searchInput = document.getElementById('galeriaSearchInput');
    const monthFilter = document.getElementById('galeriaMonthFilter');
    const sortSelect = document.getElementById('galeriaSortSelect');
    const grid = document.getElementById('galeriaGrid');
    const emptyFiltered = document.getElementById('galeriaEmptyFiltered');
    const resultCount = document.getElementById('galeriaResultCount');

    if (!grid || (!searchInput && !monthFilter && !sortSelect)) return;

    const cards = Array.from(grid.querySelectorAll('.galeria-card'));

    const applySort = () => {
        if (!sortSelect) return;
        const mode = sortSelect.value;

        const sorted = cards.slice().sort((a, b) => {
            if (mode === 'nome-az' || mode === 'nome-za') {
                const titleA = a.querySelector('h2')?.textContent.trim() || '';
                const titleB = b.querySelector('h2')?.textContent.trim() || '';
                const cmp = titleA.localeCompare(titleB, 'pt-BR');
                return mode === 'nome-az' ? cmp : -cmp;
            }
            const dateA = a.dataset.date || '';
            const dateB = b.dataset.date || '';
            return mode === 'antigos' ? dateA.localeCompare(dateB) : dateB.localeCompare(dateA);
        });

        sorted.forEach(card => grid.appendChild(card));
    };

    const applyFilter = () => {
        const term = (searchInput?.value || '').toLowerCase().trim();
        const month = monthFilter?.value || '';
        let visibleCount = 0;

        cards.forEach(card => {
            const matchesSearch = !term || card.dataset.title.includes(term);
            const matchesMonth = !month || card.dataset.month === month;
            const visible = matchesSearch && matchesMonth;
            card.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (emptyFiltered) emptyFiltered.style.display = visibleCount === 0 ? 'block' : 'none';

        if (resultCount) {
            if (term || month) {
                resultCount.style.display = 'block';
                resultCount.textContent = `${visibleCount} programaç${visibleCount === 1 ? 'ão' : 'ões'} encontrada${visibleCount !== 1 ? 's' : ''}`;
            } else {
                resultCount.style.display = 'none';
            }
        }
    };

    searchInput?.addEventListener('input', applyFilter);
    monthFilter?.addEventListener('change', applyFilter);
    sortSelect?.addEventListener('change', () => { applySort(); applyFilter(); });

    applySort();
    applyFilter();
}

/**
 * Página do evento: grade de fotos com carregamento progressivo, miniaturas
 * otimizadas, ordenação, seleção em lote (download .zip) e lightbox.
 */
function initGaleriaEventoPage() {
    const grid = document.getElementById('galeriaPhotoGrid');
    const dataEl = document.getElementById('galeriaFotosData');
    if (!grid || !dataEl) return;

    let fotos = [];
    try {
        fotos = JSON.parse(dataEl.textContent || '[]');
    } catch (err) {
        fotos = [];
    }

    const BATCH_SIZE = 24;
    const sentinel = document.getElementById('galeriaGridSentinel');
    const sortSelect = document.getElementById('galeriaPhotoSort');
    const selectToggleBtn = document.getElementById('galeriaSelectToggle');
    const batchBar = document.getElementById('galeriaBatchBar');
    const batchCountEl = document.getElementById('galeriaBatchCount');
    const batchSelectAllBtn = document.getElementById('galeriaBatchSelectAll');
    const batchDownloadBtn = document.getElementById('galeriaBatchDownload');
    const batchCancelBtn = document.getElementById('galeriaBatchCancel');

    let ordered = fotos.slice();
    let rendered = 0;
    let selectMode = false;
    const selected = new Set();

    /* ---------- Lightbox ---------- */
    const lightbox = document.getElementById('galeriaLightbox');
    const imgEl = document.getElementById('galeriaLightboxImg');
    const nameEl = document.getElementById('galeriaLightboxName');
    const counterEl = document.getElementById('galeriaLightboxCounter');
    const closeBtn = document.getElementById('galeriaLightboxClose');
    const prevBtn = document.getElementById('galeriaLightboxPrev');
    const nextBtn = document.getElementById('galeriaLightboxNext');
    const downloadBtn = document.getElementById('galeriaLightboxDownload');
    const shareBtn = document.getElementById('galeriaLightboxShare');
    const loaderEl = document.getElementById('galeriaLightboxLoader');
    const asideEl = document.querySelector('aside');

    let currentIndex = -1;

    const syncLightboxBounds = () => {
        if (!lightbox) return;
        let rightGap = 0;
        if (asideEl) {
            const rect = asideEl.getBoundingClientRect();
            if (rect.width > 0 && rect.right > rect.left) rightGap = Math.max(0, rect.width);
        }
        lightbox.style.setProperty('--galeria-lightbox-right-gap', `${rightGap}px`);
    };

    const downloadFoto = (url, name) => {
        const link = document.createElement('a');
        link.href = url;
        link.download = name;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    const shareFoto = async (url, name) => {
        const fullUrl = window.location.origin + url;
        try {
            if (navigator.share) {
                await navigator.share({ title: name, url: fullUrl });
            } else {
                await navigator.clipboard.writeText(fullUrl);
                alert('Link da foto copiado para a área de transferência!');
            }
        } catch (err) {
            console.error(err);
        }
    };

    const renderLightbox = () => {
        const foto = ordered[currentIndex];
        if (!foto || !imgEl) return;
        loaderEl?.classList.add('is-visible');
        imgEl.src = foto.url;
        imgEl.alt = foto.name;
        if (nameEl) nameEl.textContent = '';
        if (counterEl) counterEl.textContent = `${currentIndex + 1} / ${ordered.length}`;
    };

    imgEl?.addEventListener('load', () => loaderEl?.classList.remove('is-visible'));
    imgEl?.addEventListener('error', () => loaderEl?.classList.remove('is-visible'));

    const openLightbox = (index) => {
        currentIndex = index;
        renderLightbox();
        syncLightboxBounds();
        lightbox.classList.add('is-open');
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
        lightbox.classList.remove('is-open');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        currentIndex = -1;
        if (imgEl) imgEl.src = '';
    };

    const prevLightbox = () => {
        currentIndex = currentIndex > 0 ? currentIndex - 1 : ordered.length - 1;
        renderLightbox();
    };

    const nextLightbox = () => {
        currentIndex = currentIndex < ordered.length - 1 ? currentIndex + 1 : 0;
        renderLightbox();
    };

    closeBtn?.addEventListener('click', closeLightbox);
    prevBtn?.addEventListener('click', prevLightbox);
    nextBtn?.addEventListener('click', nextLightbox);
    downloadBtn?.addEventListener('click', () => {
        const foto = ordered[currentIndex];
        if (foto) downloadFoto(foto.url, foto.name);
    });
    shareBtn?.addEventListener('click', () => {
        const foto = ordered[currentIndex];
        if (foto) shareFoto(foto.url, foto.name);
    });
    lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
    window.addEventListener('resize', syncLightboxBounds);
    document.addEventListener('keydown', (e) => {
        if (!lightbox || !lightbox.classList.contains('is-open')) return;
        if (e.key === 'ArrowLeft') prevLightbox();
        if (e.key === 'ArrowRight') nextLightbox();
        if (e.key === 'Escape') closeLightbox();
    });

    /* ---------- Seleção em lote ---------- */
    const updateBatchBar = () => {
        if (!batchBar) return;
        batchBar.classList.toggle('is-visible', selectMode && selected.size > 0);
        if (batchCountEl) {
            batchCountEl.textContent = `${selected.size} selecionada${selected.size !== 1 ? 's' : ''}`;
        }
    };

    const setSelectMode = (on) => {
        selectMode = on;
        grid.classList.toggle('is-select-mode', selectMode);

        if (selectToggleBtn) {
            selectToggleBtn.classList.toggle('is-active', selectMode);
            selectToggleBtn.innerHTML = selectMode
                ? '<i class="bi bi-x-lg"></i> Cancelar seleção'
                : '<i class="bi bi-check2-square"></i> Selecionar fotos';
        }

        grid.querySelectorAll('.galeria-select-check').forEach(btn => { btn.hidden = !selectMode; });

        if (!selectMode) {
            selected.clear();
            grid.querySelectorAll('.galeria-photo-card.is-selected').forEach(card => {
                card.classList.remove('is-selected');
                const icon = card.querySelector('.galeria-select-check i');
                if (icon) icon.className = 'bi bi-circle';
            });
        }

        updateBatchBar();
    };

    const toggleSelected = (card, name) => {
        const icon = card.querySelector('.galeria-select-check i');
        if (selected.has(name)) {
            selected.delete(name);
            card.classList.remove('is-selected');
            if (icon) icon.className = 'bi bi-circle';
        } else {
            selected.add(name);
            card.classList.add('is-selected');
            if (icon) icon.className = 'bi bi-check-circle-fill';
        }
        updateBatchBar();
    };

    selectToggleBtn?.addEventListener('click', () => setSelectMode(!selectMode));
    batchCancelBtn?.addEventListener('click', () => setSelectMode(false));
    batchSelectAllBtn?.addEventListener('click', () => {
        grid.querySelectorAll('.galeria-photo-card').forEach(card => {
            if (!selected.has(card.dataset.name)) toggleSelected(card, card.dataset.name);
        });
    });
    batchDownloadBtn?.addEventListener('click', () => {
        if (selected.size === 0) return;
        const baseUrl = grid.dataset.downloadUrl;
        if (baseUrl) {
            const params = Array.from(selected).map(name => `files[]=${encodeURIComponent(name)}`).join('&');
            window.location.href = `${baseUrl}?${params}`;
            return;
        }

        // Na versão estática não há endpoint PHP para criar um ZIP. Baixa as
        // fotos escolhidas individualmente, sem depender do servidor original.
        const selectedPhotos = ordered.filter(foto => selected.has(foto.name));
        selectedPhotos.forEach((foto, index) => {
            window.setTimeout(() => downloadFoto(foto.url, foto.name), index * 250);
        });
    });

    /* ---------- Grade com carregamento progressivo ---------- */
    const createCard = (foto, index) => {
        const card = document.createElement('div');
        card.className = 'galeria-photo-card';
        card.dataset.index = String(index);
        card.dataset.url = foto.url;
        card.dataset.name = foto.name;

        card.innerHTML = `
            <div class="galeria-thumb-loader"><span class="galeria-spinner"></span></div>
            <img alt="${foto.name}" loading="lazy" decoding="async">
            <button type="button" class="galeria-select-check" data-action="select" aria-label="Selecionar foto" ${selectMode ? '' : 'hidden'}>
                <i class="bi bi-circle"></i>
            </button>
            <div class="galeria-photo-overlay">
                <button type="button" class="galeria-photo-btn" data-action="open" title="Ampliar"><i class="bi bi-arrows-fullscreen"></i></button>
                <button type="button" class="galeria-photo-btn" data-action="download" title="Baixar"><i class="bi bi-download"></i></button>
                <button type="button" class="galeria-photo-btn" data-action="share" title="Compartilhar"><i class="bi bi-share-fill"></i></button>
            </div>
        `;

        const img = card.querySelector('img');
        const loader = card.querySelector('.galeria-thumb-loader');
        img.addEventListener('load', () => loader.remove(), { once: true });
        img.addEventListener('error', () => loader.remove(), { once: true });
        img.src = foto.thumbUrl || foto.url;

        card.addEventListener('click', () => {
            if (selectMode) {
                toggleSelected(card, foto.name);
            } else {
                openLightbox(Number(card.dataset.index));
            }
        });

        card.querySelector('[data-action="select"]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSelected(card, foto.name);
        });
        card.querySelector('[data-action="open"]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            openLightbox(Number(card.dataset.index));
        });
        card.querySelector('[data-action="download"]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            downloadFoto(foto.url, foto.name);
        });
        card.querySelector('[data-action="share"]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            shareFoto(foto.url, foto.name);
        });

        return card;
    };

    let observer = null;
    if (sentinel && 'IntersectionObserver' in window) {
        observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) renderBatch();
        }, { rootMargin: '800px 0px' });
    }

    function renderBatch() {
        const next = ordered.slice(rendered, rendered + BATCH_SIZE);
        next.forEach((foto, i) => grid.appendChild(createCard(foto, rendered + i)));
        rendered += next.length;
        if (rendered >= ordered.length) observer?.disconnect();
    }

    function resetGrid() {
        grid.innerHTML = '';
        rendered = 0;
        selected.clear();
        updateBatchBar();
        renderBatch();
        if (sentinel && observer && rendered < ordered.length) observer.observe(sentinel);
    }

    sortSelect?.addEventListener('change', () => {
        const mode = sortSelect.value;
        ordered = fotos.slice();
        if (mode === 'nome-az') ordered.sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
        if (mode === 'nome-za') ordered.sort((a, b) => b.name.localeCompare(a.name, 'pt-BR'));
        resetGrid();
    });

    renderBatch();
    if (sentinel && observer && rendered < ordered.length) observer.observe(sentinel);
}
