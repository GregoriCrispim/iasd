@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Rádio e TV Novo Tempo')

@push('styles')
<!-- Glide.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.theme.min.css">
<!-- Font Awesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    html { scroll-behavior: smooth; }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #fcfaf7;
        color: #2d2a26;
    }

    /* Estilos utilitários para o carrossel */
    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .w-100 {
        width: 100%;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mx-2 {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }

    .mx-1 {
        margin-left: 0.25rem;
        margin-right: 0.25rem;
    }

    .py-4 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .col-12 {
        width: 100%;
    }

    .col {
        flex: 1;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
    }

    .row > * {
        padding-left: 15px;
        padding-right: 15px;
    }

    .position-relative {
        position: relative;
    }

    .bg-light {
        background-color: #f8f9fa;
    }

    .mb-5 {
        margin-bottom: 3rem;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        text-align: center;
        text-decoration: none;
        border: 1px solid transparent;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #001531;
        border-color: #001531;
    }

    /* Hero (1ª seção) */
    .nt-hero {
        background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 45%, #dbeafe 100%);
        border: 0;
        border-radius: 18px 18px 0 0;
        padding: 44px 40px;
        box-shadow: none;
        margin-bottom: 0;
    }

    .nt-hero .page-title {
        margin-bottom: 8px;
    }

    .nt-hero .page-subtitle {
        margin-top: 0;
        margin-bottom: 18px;
        color: rgba(15, 23, 42, 0.70);
    }

    .nt-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        margin-top: 18px;
    }

    .nt-hero-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 800;
        font-size: 0.98rem;
        border: 0;
        color: #fff;
        transition: transform 120ms ease, box-shadow 120ms ease, background 120ms ease;
        flex: 1 1 220px;
        max-width: 320px;
    }

    .nt-hero-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 26px rgba(15, 23, 42, 0.22);
    }

    .nt-hero-btn--tv {
        background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%);
    }

    .nt-hero-btn--radio {
        background: linear-gradient(135deg, #0ea5e9 0%, #0b2a4a 100%);
    }

    /* Última seção encostando no rodapé */
    .nt-main {
        padding-bottom: 0 !important;
    }

    /* Remove divisor entre hero e seção de baixo */
    .nt-main {
        padding-top: 0 !important;
    }

    .nt-main > .page-section:last-child {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .share-section {
        margin-bottom: 0 !important;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    /* Estilos do carrossel Novo Tempo */
    .pa-widget.pa-w-novotempo {
        padding: 2rem 0;
        position: relative;
        background-color: #f8f9fa;
        margin-bottom: 3rem;
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }

    .pa-widget.pa-w-novotempo .row {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        overflow: hidden;
    }

    .pa-widget.pa-w-novotempo .col {
        overflow: hidden;
        width: 100%;
    }

    /* Estilos do Glide para Novo Tempo */
    .pa-glide-novotempo {
        position: relative;
        width: 100%;
    }

    .pa-glide-novotempo .glide__track {
        overflow: hidden;
        width: 100%;
    }

    .pa-glide-novotempo .glide__slides {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pa-glide-novotempo .glide__slide {
        cursor: pointer;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        visibility: visible;
        height: 191px;
        box-sizing: border-box;
    }

    .pa-slider-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .pa-slider-header .d-flex.justify-content-between.align-items-center.w-100 {
        padding: 0 1rem;
    }

    .pa-logo-link {
        display: inline-block;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .pa-logo-link:hover {
        opacity: 0.8;
    }

    .pa-slider-header .btn-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .pa-slider-header .btn-primary:hover {
        background-color: #001531;
        border-color: #001531;
    }

    .carousel-image-link {
        position: relative;
        display: block;
        width: 100%;
        height: 191px;
        overflow: hidden;
        border-radius: 0.5rem;
        flex-shrink: 0;
    }

    .carousel-image-link img {
        display: block;
        width: 100%;
        height: 191px;
        object-fit: cover;
        border-radius: 0.5rem;
        transition: transform 0.3s ease;
    }

    .pa-glide-novotempo .glide__slide:hover .carousel-image-link img {
        transform: scale(1.05);
    }

    .pa-slider-controle {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .pa-slider-btn {
        background: #003366;
        border: none;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 1.2rem;
    }

    .pa-slider-btn:hover {
        background: #001531;
    }

    .pa-slider-bullet {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
    }

    .pa-slider-bullet i {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
    }

    .pa-slider-bullet i.active,
    .pa-slider-bullet i:hover {
        color: #003366;
    }

    .fa-circle {
        font-size: 0.5rem;
    }

    .fa-xs {
        font-size: 0.75em;
    }

    /* Cards de Mídia */
    .novotempo-media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .novotempo-media-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
    }

    .novotempo-media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .novotempo-media-card-header {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        padding: 30px 20px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .novotempo-media-card-header svg {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .novotempo-media-card-header h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        margin: 0;
        font-weight: 500;
    }

    .novotempo-media-card-body {
        padding: 25px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
        box-sizing: border-box;
    }

    .novotempo-media-card-body ul {
        list-style: disc;
        list-style-position: inside;
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .novotempo-media-card-body ul li {
        margin-bottom: 10px;
    }

    .novotempo-media-info {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        background: #f8f9fa;
        padding: 18px;
        border-radius: 8px;
        font-style: italic;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .novotempo-media-info strong {
        color: #003366;
        font-style: normal;
    }

    .btn-novotempo {
        display: block;
        width: 100%;
        max-width: 100%;
        text-align: center;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        font-weight: 700;
        padding: 14px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        margin-top: auto;
        box-sizing: border-box;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .btn-novotempo:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .btn-novotempo.btn-radio {
        background: linear-gradient(135deg, #1e3a8a 0%, #001531 100%);
    }

    /* Seção de Compartilhar */
    .share-section {
        background: linear-gradient(135deg, #eef2f6 0%, #dbeafe 100%);
        padding: 50px 30px;
        border-radius: 16px;
        border: 2px dashed #bfdbfe;
        margin: 50px 0;
        text-align: center;
    }

    .share-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        font-weight: 500;
        color: #003366;
        margin-bottom: 25px;
        font-style: italic;
    }

    .share-blockquote {
        font-family: 'Roboto', sans-serif;
        font-size: 1.4em;
        font-weight: 300;
        color: #1e40af;
        margin: 0 auto 20px;
        max-width: 800px;
        font-style: italic;
        line-height: 1.6;
    }

    .share-cite {
        color: #003366;
        font-weight: 700;
        font-size: 1.1em;
        font-family: 'Roboto', sans-serif;
        font-style: normal;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .nt-hero {
            padding: 34px 18px;
            border-radius: 16px;
        }

        .nt-hero-actions {
            gap: 10px;
        }

        .nt-hero-btn {
            max-width: 100%;
        }

        .novotempo-media-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .novotempo-media-card-header {
            flex-direction: column;
            gap: 10px;
        }

        .novotempo-media-card-header h3 {
            font-size: 1.5em;
        }

        .share-section {
            padding: 35px 20px;
        }

        .share-section h2 {
            font-size: 1.8em;
        }

        .share-blockquote {
            font-size: 1.2em;
        }

        .pa-widget.pa-w-novotempo .row {
            padding: 0 0.5rem;
            overflow: visible;
        }

        .pa-slider-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .pa-slider-header .d-flex.justify-content-between.align-items-center.w-100 {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-align: center;
        }

        .pa-slider-header .btn-primary {
            padding: 10px 16px;
            font-size: 0.95rem;
            border-radius: 999px;
        }

        .pa-slider-controle {
            display: grid !important;
            grid-template-columns: 36px 1fr 36px;
            align-items: center;
            gap: 10px;
            padding: 0 8px;
        }

        .pa-slider-controle .pa-slider-bullet {
            overflow-x: auto;
            justify-content: center;
            padding: 4px 0;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .pa-slider-controle .pa-slider-bullet::-webkit-scrollbar {
            display: none;
        }

        .pa-slider-bullet {
            gap: 0.25rem;
        }

        .pa-slider-bullet i {
            margin: 0 2px !important;
        }

        .fa-circle {
            font-size: 0.42rem;
        }

        .pa-slider-btn {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .pa-glide-novotempo .glide__slide {
            height: auto;
        }

        .carousel-image-link {
            width: 100%;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        .carousel-image-link img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            inset: 0;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Glide.js JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js"></script>
<script>
    // Carrossel Novo Tempo
    const apiUrlNovoTempo = (window.APP_URL || '') + '/api/videos-novotempo';
    
    fetch(apiUrlNovoTempo)
        .then(response => response.json())
        .then(videos => {
            if (!videos || videos.length === 0 || videos.error) {
                console.error('Erro ao carregar vídeos do Novo Tempo:', videos.error || 'Nenhum vídeo encontrado');
                return;
            }

            const slidesContainer = document.getElementById('novotempo-slides');
            const bulletsContainer = document.getElementById('novotempo-bullets');
            
            if (!slidesContainer || !bulletsContainer) return;

            // Limpar containers
            slidesContainer.innerHTML = '';
            bulletsContainer.innerHTML = '';

            // Criar slides
            videos.forEach((video, index) => {
                const slide = document.createElement('div');
                slide.className = 'glide__slide';
                slide.innerHTML = `
                    <a href="https://www.youtube.com/watch?v=${video.id}" target="_blank" rel="noopener noreferrer" class="carousel-image-link">
                        <img 
                            src="${video.thumbnail}" 
                            alt="${video.title}" 
                            loading="lazy" decoding="async"
                        />
                    </a>
                `;
                slidesContainer.appendChild(slide);

                // Criar bullet
                const bullet = document.createElement('i');
                bullet.className = 'fas fa-circle fa-xs mx-1';
                bullet.setAttribute('data-glide-dir', `=${index}`);
                bulletsContainer.appendChild(bullet);
            });

            // Inicializar carrossel após criar os slides
            const novoTempoCarousel = document.querySelector('.pa-glide-novotempo');
            if (novoTempoCarousel && videos.length > 0) {
                const autoplay = novoTempoCarousel.getAttribute('data-autoplay') || 2500;
                
                const glideNovoTempo = new Glide('.pa-glide-novotempo', {
                    type: 'carousel',
                    startAt: 0,
                    perView: 4,
                    gap: 20,
                    rewind: true,
                    autoplay: parseInt(autoplay),
                    hoverpause: true,
                    animationDuration: 600,
                    animationTimingFunc: 'ease-in-out',
                    peek: {
                        before: 0,
                        after: 0
                    },
                    perTouch: 1,
                    swipeThreshold: 80,
                    dragThreshold: 120,
                    breakpoints: {
                        1200: {
                            perView: 3,
                            gap: 20,
                            rewind: true
                        },
                        768: {
                            perView: 2,
                            gap: 20,
                            rewind: true
                        },
                        576: {
                            perView: 1,
                            gap: 0,
                            rewind: true
                        }
                    }
                });

                // Função para atualizar bullets ativos do Novo Tempo
                function updateBulletsNovoTempo() {
                    const bullets = document.querySelectorAll('#novotempo-bullets i');
                    const totalSlides = bullets.length;
                    if (totalSlides === 0) return;
                    
                    let currentIndex = glideNovoTempo.index;
                    
                    // Normalizar o índice para valores negativos ou maiores que o total
                    if (currentIndex < 0) {
                        currentIndex = ((currentIndex % totalSlides) + totalSlides) % totalSlides;
                    } else if (currentIndex >= totalSlides) {
                        currentIndex = currentIndex % totalSlides;
                    }
                    
                    bullets.forEach((bullet, index) => {
                        if (index === currentIndex) {
                            bullet.classList.add('active');
                        } else {
                            bullet.classList.remove('active');
                        }
                    });
                }

                glideNovoTempo.mount();

                glideNovoTempo.on('mount.after', function() {
                    updateBulletsNovoTempo();
                    setTimeout(function() {
                        glideNovoTempo.update();
                        updateBulletsNovoTempo();
                    }, 100);
                });

                // Atualizar bullets após a conclusão da movimentação
                glideNovoTempo.on('run.after', updateBulletsNovoTempo);
                
                setTimeout(function() {
                    updateBulletsNovoTempo();
                }, 200);
            }
        })
        .catch(error => console.error('Erro ao carregar vídeos do Novo Tempo:', error));
</script>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/radio_tv_novo_tempo/radio_tv_novo_tempo_header.webp') }}" alt="Rádio e TV Novo Tempo" fetchpriority="high" decoding="async">

<div class="page-container" style="padding-bottom: 0;">
    <div class="page-hero nt-hero acb-fullbleed">
        <h1 class="page-title acb-title-serif">Rádio e TV Novo Tempo</h1>
        <h2 class="page-subtitle" style="font-style: italic;">Conectando Você à Esperança!</h2>
        <p style="background: linear-gradient(135deg, #6d28d9 0%, #4c1d95 100%); color: #fff; padding: 12px 20px; border-radius: 10px; text-align: center; font-weight: 700; font-size: 1.1rem; margin: 12px 0;">
            <i class="fa-solid fa-tv"></i> Em Brasília, assista na TV aberta: <strong>Canal 48</strong>
        </p>
        <p class="page-text">
            Bem-vindo(a) à janela digital da <strong>TV e Rádio Novo Tempo</strong>, um ministério da Igreja Adventista do Sétimo Dia dedicado a levar mensagens de fé, saúde, família e esperança diretamente para o seu coração! Aqui, você encontrará programação de qualidade para inspirar seu dia a dia, fortalecer sua espiritualidade e oferecer conteúdo positivo para toda a família.
        </p>

        <div class="nt-hero-actions">
            <a class="nt-hero-btn nt-hero-btn--tv" href="https://www.novotempo.com/tv/ao-vivo/" target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-tv"></i> Assistir TV ao vivo
            </a>
            <a class="nt-hero-btn nt-hero-btn--radio" href="https://www.novotempo.com/radio/#onde-ouvir" target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-radio"></i> Ouvir rádio ao vivo
            </a>
        </div>
    </div>
</div>

<div class="page-container nt-main" style="padding-top: 0;">
    <div class="page-section">
        <div class="novotempo-media-grid">
            <!-- TV Section -->
            <div class="novotempo-media-card">
                <div class="novotempo-media-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3>TV Novo Tempo</h3>
                </div>
                <div class="novotempo-media-card-body">
                    <ul>
                        <li><strong>Programação 24h:</strong> Conteúdo educativo, devocionais, séries infantis, documentários e estudos bíblicos.</li>
                    </ul>
                    <p class="novotempo-media-info">
                        <strong>Como assistir em Brasília:</strong> Disponível em canal aberto na TV (Canal 48.1), streaming ou em nossa plataforma online.
                    </p>
                    <a href="https://www.novotempo.com/tv/ao-vivo/" target="_blank" rel="noopener noreferrer" class="btn-novotempo">
                        Assistir TV Novo Tempo AO VIVO
                    </a>
                </div>
            </div>

            <!-- Radio Section -->
            <div class="novotempo-media-card">
                <div class="novotempo-media-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    <h3>Rádio Novo Tempo</h3>
                </div>
                <div class="novotempo-media-card-body">
                    <ul>
                        <li><strong>Música e Mensagens:</strong> Programas com mensagens edificantes, notícias inspiradoras e louvores que renovam o ânimo.</li>
                    </ul>
                    <p class="novotempo-media-info">
                        <strong>Como ouvir:</strong> Sintonize nossa frequência ou escute online de qualquer lugar!
                    </p>
                    <a href="https://www.novotempo.com/radio/#onde-ouvir" target="_blank" rel="noopener noreferrer" class="btn-novotempo btn-radio">
                        Ouvir Rádio Novo Tempo AO VIVO
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Carrossel de Vídeos Novo Tempo -->
    <div class="page-section">
        <div class="pa-widget pa-w-novotempo py-4 col-12 position-relative bg-light mb-5">
            <div class="pa-slider-header mb-4">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <a 
                        href="https://www.youtube.com/@novotempo" 
                        target="_blank"
                        rel="noopener"
                        class="pa-logo-link"
                    >
                        <h3 style="margin: 0; color: #003366; font-family: 'Bebas neue', sans-serif; font-size: 1.8em;">Novo Tempo</h3>
                    </a>
                    <a 
                        href="https://www.youtube.com/@novotempo" 
                        target="_blank"
                        class="btn btn-primary"
                        rel="noopener"
                    >
                        Ver canal
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="pa-glide-novotempo" 
                         data-autoplay="2500"
                         data-format="100">
                        <div class="glide__track" data-glide-el="track">
                            <div class="glide__slides" id="novotempo-slides">
                                <!-- Slides serão preenchidos via JavaScript -->
                            </div>
                        </div>
                        
                        <div class="pa-slider-controle d-flex align-items-center mt-4">
                            <div data-glide-el="controls">
                                <button type="button" class="pa-slider-btn" data-glide-dir="&lt;" aria-label="Anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </div>
                            <div class="mx-2 pa-slider-bullet" id="novotempo-bullets" data-glide-el="controls[nav]">
                                <!-- Bullets serão preenchidos via JavaScript -->
                            </div>
                            <div data-glide-el="controls">
                                <button type="button" class="pa-slider-btn" data-glide-dir="&gt;" aria-label="Próximo">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>									
                </div>
            </div>
        </div>
    </div>

    <!-- Compartilhe a Esperança -->
    <div class="page-section">
        <div class="share-section acb-fullbleed">
            <h2 class="acb-title-serif">Compartilhe a Esperança!</h2>
            <blockquote class="acb-quote" style="max-width: 800px; margin: 0 auto;">
                <p>"E este evangelho do reino será pregado em todo o mundo como testemunho a todas as nações."</p>
                <span class="acb-quote__ref">(Mateus 24:14)</span>
            </blockquote>
        </div>
    </div>
</div>
@endsection
