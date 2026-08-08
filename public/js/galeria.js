document.addEventListener('DOMContentLoaded', () => {
    initGaleriaCarrossel();
    initGaleriaLazyImages();
    initGaleriaFiltro();
    initGaleriaEventoPage();
});

function escapeGaleriaHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function initGaleriaLazyImages() {
    const images = Array.from(document.querySelectorAll('img[data-galeria-lazy][data-src]'));
    if (images.length === 0) return;

    const load = (img) => {
        if (!img.dataset.src || img.dataset.loaded === '1') return;
        const url = img.dataset.src;
        img.src = url;
        img.removeAttribute('data-src');
        const mark = () => {
            img.loading = 'eager';
            img.fetchPriority = 'auto';
            img.dataset.loaded = '1';
        };
        if (img.complete && img.naturalWidth > 0) {
            mark();
            return;
        }
        img.addEventListener('load', mark, { once: true });
        img.addEventListener('error', mark, { once: true });
    };

    if (!('IntersectionObserver' in window)) {
        images.forEach(load);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            load(entry.target);
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '500px 0px' });

    images.forEach((img) => observer.observe(img));
}

/**
 * Página de listagem: carrossel de fotos recentes.
 * Carrega uma imagem por vez; enquanto a atual está na tela, pré-carrega a próxima.
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

    const slideImage = (index) => slides[index]?.querySelector('img');

    const loadSlideImage = (index) => new Promise((resolve) => {
        const image = slideImage(index);
        if (!image) {
            resolve();
            return;
        }
        if (image.dataset.loaded === '1') {
            resolve();
            return;
        }

        const url = image.dataset.src || image.getAttribute('src');
        if (!url) {
            resolve();
            return;
        }

        const mark = () => {
            image.loading = 'eager';
            image.fetchPriority = 'auto';
            image.dataset.loaded = '1';
            resolve();
        };

        if (!image.getAttribute('src') || image.dataset.src) {
            image.src = url;
            image.removeAttribute('data-src');
        }

        if (image.complete && image.naturalWidth > 0) {
            mark();
            return;
        }

        image.addEventListener('load', mark, { once: true });
        image.addEventListener('error', mark, { once: true });
    });

    const preloadNext = () => {
        if (slides.length < 2) return;
        void loadSlideImage((current + 1) % slides.length);
    };

    const show = (index) => {
        current = (index + slides.length) % slides.length;
        void loadSlideImage(current).then(preloadNext);
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
 * Página do evento: grade de fotos com paginação, cache de páginas já visitadas,
 * miniaturas otimizadas, seleção em lote (download .zip) e lightbox.
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

    const PAGE_SIZE = Math.max(1, parseInt(grid.dataset.pageSize || '24', 10) || 24);
    const paginationEl = document.getElementById('galeriaPagination');
    const selectToggleBtn = document.getElementById('galeriaSelectToggle');
    const batchBar = document.getElementById('galeriaBatchBar');
    const batchCountEl = document.getElementById('galeriaBatchCount');
    const batchSelectAllBtn = document.getElementById('galeriaBatchSelectAll');
    const batchDownloadBtn = document.getElementById('galeriaBatchDownload');
    const batchCancelBtn = document.getElementById('galeriaBatchCancel');

    let ordered = fotos.slice();
    let currentPage = 1;
    let selectMode = false;
    const selected = new Set();
    /** @type {Map<number, HTMLElement[]>} */
    const pageCache = new Map();
    const loadedThumbs = new Set();

    const markThumbLoaded = (img, url) => {
        if (!img) return;
        if (url) loadedThumbs.add(url);
        img.loading = 'eager';
        img.fetchPriority = 'auto';
        img.dataset.loaded = '1';
        img.removeAttribute('data-src');
    };

    const assignThumb = (img, url) => {
        if (!img || !url) return;
        if (img.dataset.loaded === '1' && (img.currentSrc === url || img.getAttribute('src') === url)) {
            return;
        }
        if (loadedThumbs.has(url) || img.complete && img.naturalWidth > 0 && img.getAttribute('src') === url) {
            img.src = url;
            markThumbLoaded(img, url);
            return;
        }
        img.src = url;
        img.removeAttribute('data-src');
    };

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

    // Grade = miniatura. Overlay = versão de exibição. Download = arquivo guardado (maior qualidade).
    const fotoPreviewUrl = (foto) => foto.url || foto.downloadUrl;
    const fotoDownloadUrl = (foto) => foto.downloadUrl || foto.url;
    const fotoDownloadName = (foto) => foto.downloadName || foto.name;

    const downloadFoto = (url, name) => {
        const link = document.createElement('a');
        link.href = url;
        link.download = name;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    const shareFoto = async (url, name) => {
        const fullUrl = /^https?:\/\//i.test(url) ? url : (window.location.origin + url);
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
        imgEl.src = fotoPreviewUrl(foto);
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
        if (foto) downloadFoto(fotoDownloadUrl(foto), fotoDownloadName(foto));
    });
    shareBtn?.addEventListener('click', () => {
        const foto = ordered[currentIndex];
        if (foto) shareFoto(fotoPreviewUrl(foto), foto.name);
    });
    lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
    window.addEventListener('resize', syncLightboxBounds);
    document.addEventListener('keydown', (e) => {
        if (!lightbox || !lightbox.classList.contains('is-open')) return;
        if (e.key === 'ArrowLeft') prevLightbox();
        if (e.key === 'ArrowRight') nextLightbox();
        if (e.key === 'Escape') closeLightbox();
    });

    /* ---------- Seleção em lote (bolinhas só na página atual) ---------- */
    const updateBatchBar = () => {
        if (!batchBar) return;
        batchBar.classList.toggle('is-visible', selectMode && selected.size > 0);
        if (batchCountEl) {
            batchCountEl.textContent = `${selected.size} selecionada${selected.size !== 1 ? 's' : ''}`;
        }
    };

    const syncCardSelection = (card) => {
        card.classList.toggle('is-selected', selected.has(card.dataset.name));
    };

    /**
     * A bolinha nasce junto com o card da página atual e a visibilidade é só
     * CSS: ativar a seleção custa uma troca de classe, sem mexer no DOM.
     */
    const setSelectMode = (on) => {
        selectMode = !!on;
        grid.classList.toggle('is-select-mode', selectMode);

        // Os dois rótulos já estão no HTML; alternar é só classe (sem reparse).
        selectToggleBtn?.classList.toggle('is-active', selectMode);

        if (!selectMode && selected.size > 0) {
            selected.clear();
            for (const card of grid.children) card.classList.remove('is-selected');
        }

        updateBatchBar();
    };

    const toggleSelected = (card, name) => {
        if (selected.has(name)) selected.delete(name);
        else selected.add(name);
        syncCardSelection(card);
        updateBatchBar();
    };

    selectToggleBtn?.addEventListener('click', () => setSelectMode(!selectMode));
    batchCancelBtn?.addEventListener('click', () => setSelectMode(false));
    batchSelectAllBtn?.addEventListener('click', () => {
        for (const card of grid.children) {
            if (!selected.has(card.dataset.name)) toggleSelected(card, card.dataset.name);
        }
    });
    batchDownloadBtn?.addEventListener('click', () => {
        if (selected.size === 0) return;
        const baseUrl = grid.dataset.downloadUrl;
        if (baseUrl) {
            const params = Array.from(selected).map(name => `files[]=${encodeURIComponent(name)}`).join('&');
            window.location.href = `${baseUrl}?${params}`;
            return;
        }

        const selectedPhotos = ordered.filter(foto => selected.has(foto.name));
        selectedPhotos.forEach((foto, index) => {
            window.setTimeout(() => downloadFoto(fotoDownloadUrl(foto), fotoDownloadName(foto)), index * 250);
        });
    });

    /* ---------- Grade com paginação e cache ---------- */
    const totalPages = () => Math.max(1, Math.ceil(ordered.length / PAGE_SIZE));

    const createCard = (foto, index) => {
        const card = document.createElement('div');
        card.className = 'galeria-photo-card';
        card.dataset.index = String(index);
        card.dataset.url = foto.url;
        card.dataset.name = foto.name;

        // Sempre a miniatura na grade — nunca a versão de overlay/download.
        const thumbUrl = foto.thumbUrl;
        if (!thumbUrl) {
            card.innerHTML = `<div class="galeria-thumb-loader"><span class="galeria-spinner"></span></div>`;
            return card;
        }

        card.innerHTML = `
            <div class="galeria-thumb-loader"><span class="galeria-spinner"></span></div>
            <img alt="${escapeGaleriaHtml(foto.name)}" decoding="async" loading="lazy">
            <button type="button" class="galeria-select-check" data-action="select" aria-label="Selecionar foto"></button>
        `;

        const img = card.querySelector('img');
        const loader = card.querySelector('.galeria-thumb-loader');
        const hideLoader = () => {
            loader?.remove();
            markThumbLoaded(img, thumbUrl);
        };

        if (loadedThumbs.has(thumbUrl)) {
            img.src = thumbUrl;
            hideLoader();
        } else {
            img.addEventListener('load', hideLoader, { once: true });
            img.addEventListener('error', hideLoader, { once: true });
            assignThumb(img, thumbUrl);
        }

        card.querySelector('[data-action="select"]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSelected(card, foto.name);
        });

        card.addEventListener('click', () => {
            if (selectMode) {
                toggleSelected(card, foto.name);
            } else {
                openLightbox(Number(card.dataset.index));
            }
        });

        return card;
    };

    const buildPageCards = (page) => {
        const start = (page - 1) * PAGE_SIZE;
        return ordered.slice(start, start + PAGE_SIZE).map((foto, i) => createCard(foto, start + i));
    };

    const pageWindow = (page, pages) => {
        if (pages <= 7) {
            return Array.from({ length: pages }, (_, i) => i + 1);
        }
        const items = [1];
        const start = Math.max(2, page - 1);
        const end = Math.min(pages - 1, page + 1);
        if (start > 2) items.push('…');
        for (let i = start; i <= end; i++) items.push(i);
        if (end < pages - 1) items.push('…');
        items.push(pages);
        return items;
    };

    const renderPagination = () => {
        if (!paginationEl) return;
        const pages = totalPages();
        if (pages <= 1) {
            paginationEl.hidden = true;
            paginationEl.innerHTML = '';
            return;
        }

        paginationEl.hidden = false;
        const start = (currentPage - 1) * PAGE_SIZE + 1;
        const end = Math.min(currentPage * PAGE_SIZE, ordered.length);
        const buttons = [
            `<button type="button" class="galeria-page-btn" data-page="prev" ${currentPage === 1 ? 'disabled' : ''} aria-label="Página anterior"><i class="bi bi-chevron-left"></i></button>`,
        ];

        pageWindow(currentPage, pages).forEach((item) => {
            if (item === '…') {
                buttons.push('<span class="galeria-page-ellipsis" aria-hidden="true">…</span>');
                return;
            }
            buttons.push(
                `<button type="button" class="galeria-page-btn${item === currentPage ? ' is-active' : ''}" data-page="${item}" aria-label="Página ${item}"${item === currentPage ? ' aria-current="page"' : ''}>${item}</button>`
            );
        });

        buttons.push(
            `<button type="button" class="galeria-page-btn" data-page="next" ${currentPage === pages ? 'disabled' : ''} aria-label="Próxima página"><i class="bi bi-chevron-right"></i></button>`,
            `<div class="galeria-page-info">${start}–${end} de ${ordered.length} fotos</div>`
        );

        paginationEl.innerHTML = buttons.join('');
    };

    const showPage = (page, { scroll = true } = {}) => {
        const pages = totalPages();
        currentPage = Math.min(Math.max(1, page), pages);

        let cards = pageCache.get(currentPage);
        if (!cards) {
            cards = buildPageCards(currentPage);
            pageCache.set(currentPage, cards);
        }

        grid.replaceChildren(...cards);
        for (const card of cards) syncCardSelection(card);
        renderPagination();

        if (scroll) {
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    paginationEl?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-page]');
        if (!btn || btn.disabled) return;
        const value = btn.getAttribute('data-page');
        if (value === 'prev') showPage(currentPage - 1);
        else if (value === 'next') showPage(currentPage + 1);
        else showPage(Number(value));
    });

    /* ---------- Filtro por busca facial ---------- */
    // O módulo face-search.js chama estes ganchos para reduzir a grade às fotos
    // encontradas (por id estável) ou restaurar a lista completa.
    const allFotos = fotos.slice();

    const rebuildAfterFilter = () => {
        pageCache.clear();
        selected.clear();
        if (selectMode) setSelectMode(false);
        showPage(1, { scroll: false });
        updateBatchBar();
    };

    window.galeriaApplyFaceFilter = (ids) => {
        const set = new Set((ids || []).map(Number));
        ordered = allFotos.filter((foto) => set.has(Number(foto.id)));
        rebuildAfterFilter();
        return ordered.length;
    };

    window.galeriaClearFaceFilter = () => {
        ordered = allFotos.slice();
        rebuildAfterFilter();
        return ordered.length;
    };

    showPage(1, { scroll: false });
}
