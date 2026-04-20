// Usa a URL base da aplicação (definida no layout) ou fallback para caminho relativo
const apiUrl = (window.APP_URL || '') + '/api/videos-youtube';
const ytStage = document.querySelector('[data-yt-stage]');
const ytLoader = document.querySelector('[data-yt-loader]');
const ytContent = document.querySelector('[data-yt-content]');

function setYoutubeState(state) {
    if (!ytStage) return;

    ytStage.classList.remove('is-loading', 'is-ready', 'is-error');
    ytStage.classList.add(state);

    if (ytContent) {
        ytContent.setAttribute('aria-hidden', state === 'is-loading' ? 'true' : 'false');
    }

    if (ytLoader) {
        ytLoader.setAttribute('aria-hidden', state === 'is-loading' ? 'false' : 'true');
    }
}

function buildEmbedUrl(id, { autoplay } = { autoplay: false }) {
    const qs = new URLSearchParams({
        controls: '0',
        autoplay: autoplay ? '1' : '0',
    });
    return `https://www.youtube.com/embed/${id}?${qs.toString()}`;
}

function initLazyPlayer(video) {
    const btn = document.querySelector('[data-yt-lazy]');
    if (!btn || !video) return;

    const thumb = btn.querySelector('.yt_lazy_thumb');
    if (thumb && video.thumbnail) {
        thumb.src = video.thumbnail;
        thumb.alt = video.title ? `Thumbnail: ${video.title}` : '';
    }

    btn.setAttribute('data-yt-id', video.id);
    if (video.title) btn.setAttribute('aria-label', `Reproduzir: ${video.title}`);

    if (btn.dataset.bound === '1') return;
    btn.dataset.bound = '1';

    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-yt-id');
        if (!id) return;

        const iframe = document.createElement('iframe');
        iframe.width = '560';
        iframe.height = '315';
        iframe.src = buildEmbedUrl(id, { autoplay: true });
        iframe.title = 'YouTube video player';
        iframe.frameBorder = '0';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        iframe.allowFullscreen = true;
        iframe.loading = 'lazy';

        btn.replaceWith(iframe);
    }, { passive: true });
}

fetch(apiUrl)
    .then(response => {
        if (!response.ok) {
            throw new Error('Falha ao carregar os videos');
        }

        return response.json();
    })
    .then(videos => {
        if (!videos || videos.length === 0) {
            setYoutubeState('is-error');
            return;
        }

        initLazyPlayer(videos[0]);

        const thumbs = document.querySelectorAll('.thumb_em_breve');
        videos.slice(1, 4).forEach((video, index) => {
            if (thumbs[index]) {
                const img = thumbs[index].querySelector('img');
                const thumbLink = thumbs[index].querySelector('.thumb_em_breve_link');
                const titleLink = thumbs[index].querySelector('.thumb_em_breve_title');
                const videoUrl = `https://www.youtube.com/watch?v=${video.id}`;

                img.src = video.thumbnail;
                if (thumbLink) {
                    thumbLink.href = videoUrl;
                    thumbLink.target = '_blank';
                    thumbLink.rel = 'noopener noreferrer';
                    thumbLink.setAttribute('aria-label', `Abrir vídeo no YouTube: ${video.title}`);
                }
                if (titleLink) {
                    titleLink.href = videoUrl;
                    titleLink.target = '_blank';
                    titleLink.rel = 'noopener noreferrer';
                    titleLink.textContent = video.title;
                }
            }
        });

        setYoutubeState('is-ready');
    })
    .catch(error => {
        console.error('Erro ao carregar videos:', error);
        setYoutubeState('is-error');
    });