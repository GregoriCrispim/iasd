@extends('layouts.app')

@section('title', 'IASD Central de Brasília - CPB Casa Publicadora Brasileira')

@section('meta-description', 'Acesse livros, Bíblias e materiais da CPB - Casa Publicadora Brasileira. Literatura cristã de qualidade na IASD Central de Brasília.')
@section('og-title', 'CPB - Casa Publicadora Brasileira')
@section('og-description', 'Literatura que transforma vidas! Conheça os livros e materiais da CPB.')
@section('page-name', 'CPB')

@php
// Definição dos dados dos produtos em Array PHP para fácil manutenção
$products = [
    [
        "title" => "Bíblias e Livros Devocionais",
        "icon" => "book-open",
        "items" => [
            ["name" => "Bíblias de Estudo", "desc" => "Como a Bíblia Andrews, perfeita para mergulhar em análises profundas das Escrituras."],
            ["name" => "Livros Inspiradores", "desc" => "Obras de Ellen G. White, como Caminho a Cristo e O Desejado de Todas as Nações."]
        ]
    ],
    [
        "title" => "Revistas e Materiais Educativos",
        "icon" => "book",
        "items" => [
            ["name" => "Revista Adventista e Nosso Amiguinho", "desc" => "Conteúdo para todas as idades, desde crianças até adultos."],
            ["name" => "Lições da Escola Sabatina", "desc" => "Estudos semanais que unem famílias e grupos em torno da Palavra."]
        ]
    ],
    [
        "title" => "Alimentos e Vida Saudável",
        "icon" => "coffee",
        "items" => [
            ["name" => "Livros de Culinária", "desc" => "Receitas nutritivas e vegetarianas para uma vida equilibrada."],
            ["name" => "Publicações sobre Saúde", "desc" => "Dicas práticas baseadas no conceito bíblico de cuidar do corpo."],
            ["name" => "Produtos Naturais", "desc" => "Alimentos integrais que refletem o estilo de vida adventista."]
        ]
    ],
    [
        "title" => "Produtos para a Família",
        "icon" => "users",
        "items" => [
            ["name" => "Guias Práticos", "desc" => "Para educação, saúde e relacionamentos, alinhados aos princípios cristãos."]
        ]
    ]
];

// Prepara o endereço para os links de GPS
$addressString = "Setor Comercial Norte Q 1 Bloco A Edifício Number One, Brasília";
$addressEncoded = urlencode($addressString);
@endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.theme.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

<style>
    html { scroll-behavior: smooth; }

    /* Background da seção hero igual às páginas profecias e evidencias-biblicas */
    .page-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
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

    /* Estilos do carrossel Casa Publicadora */
    .pa-widget.pa-w-casapublicadora {
        padding: 2rem 0;
        position: relative;
        background-color: #f8f9fa;
        margin-bottom: 3rem;
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }

    .pa-widget.pa-w-casapublicadora .row {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        overflow: hidden;
    }

    .pa-widget.pa-w-casapublicadora .col {
        overflow: hidden;
        width: 100%;
    }

    /* Estilos do Glide para Casa Publicadora */
    .pa-glide-casapublicadora {
        position: relative;
        width: 100%;
    }

    .pa-glide-casapublicadora .glide__track {
        overflow: hidden;
        width: 100%;
    }

    .pa-glide-casapublicadora .glide__slides {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pa-glide-casapublicadora .glide__slide {
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

    .pa-glide-casapublicadora .glide__slide:hover .carousel-image-link img {
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
    
    /* Estilos específicos para a página CPB */
    .cpb-products-grid {
        max-width: 1200px;
        margin: 30px auto 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
    }
    
    .cpb-product-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .cpb-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .cpb-product-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        transition: transform 0.3s;
    }
    
    .cpb-product-card:hover .cpb-product-icon {
        transform: scale(1.1);
    }
    
    .cpb-product-icon.amber {
        background: #fef3c7;
    }
    
    .cpb-product-icon.indigo {
        background: #e0e7ff;
    }
    
    .cpb-product-icon.pink {
        background: #fce7f3;
    }
    
    .cpb-product-icon.cyan {
        background: #cffafe;
    }
    
    .cpb-product-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }
    
    .cpb-product-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .cpb-product-list li {
        margin-bottom: 15px;
    }
    
    .cpb-product-list li:last-child {
        margin-bottom: 0;
    }
    
    .cpb-product-name {
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        color: #003366;
        display: block;
        margin-bottom: 5px;
    }
    
    .cpb-product-desc {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }
    
    .cpb-store-section {
        margin-top: 45px;
    }
    
    .cpb-store-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .cpb-store-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #003366;
        margin-bottom: 15px;
    }
    
    .cpb-store-label i {
        font-size: 1.2rem;
    }
    
    .cpb-store-label span {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .cpb-store-intro {
        text-align: center;
        font-style: italic;
        max-width: 800px;
        margin: 0 auto 30px;
    }
    
    .cpb-store-image-container {
        max-width: 900px;
        margin: 0 auto 30px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .cpb-store-image-container img {
        width: 100%;
        height: auto;
        display: block;
        min-height: 400px;
        object-fit: cover;
    }
    
    .cpb-store-info {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-top: 4px solid rgba(0, 51, 102, 0.22);
        border-radius: 14px;
        padding: 28px;
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 22px;
        align-items: start;
    }
    
    .cpb-address-box {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 0;
        text-align: left;
        align-self: center;
    }
    
    .cpb-address-box strong {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #003366;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .cpb-address-box strong i {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e0e7ff;
        color: #003366;
        font-size: 1.05rem;
        flex: 0 0 auto;
    }
    
    .cpb-address-box span {
        display: block;
        color: #333;
        font-family: 'Roboto', sans-serif;
        margin-bottom: 0;
        line-height: 1.6;
    }
    
    .cpb-gps-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
        align-items: stretch;
    }
    
    .cpb-gps-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2f6;
        color: #003366;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.3s, transform 0.3s;
        border: 1px solid #d7dde5;
        justify-content: center;
        flex: 1 1 0;
        min-width: 160px;
        min-height: 46px;
        text-align: center;
    }

    .cpb-gps-button i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        line-height: 1;
    }

    .cpb-gps-button i::before {
        line-height: 1;
        display: block;
    }

    .cpb-gps-button span {
        display: inline-flex;
        align-items: center;
        line-height: 1.1;
    }
    
    .cpb-gps-button:hover {
        background: #d1d5db;
        transform: translateY(-1px);
    }

    .cpb-gps-button.cpb-gps-waze {
        background: #e0f2fe;
        border-color: #bae6fd;
        color: #0b4a6f;
    }

    .cpb-gps-button.cpb-gps-waze:hover {
        background: #bae6fd;
    }

    .cpb-gps-button.cpb-gps-maps {
        background: #e0e7ff;
        border-color: #c7d2fe;
        color: #1e3a8a;
    }

    .cpb-gps-button.cpb-gps-maps:hover {
        background: #c7d2fe;
    }
    
    .cpb-contact-info {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
        margin-bottom: 0;
    }
    
    .cpb-contact-item {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 16px 18px;
        text-align: left;
    }
    
    .cpb-contact-item strong {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #003366;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .cpb-contact-item strong i {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fef3c7;
        color: #003366;
        font-size: 1.05rem;
        flex: 0 0 auto;
    }
    
    .cpb-contact-item span,
    .cpb-contact-item a {
        display: block;
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
    }
    
    .cpb-contact-item a {
        color: #16a34a;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .cpb-contact-item a:hover {
        color: #15803d;
    }

    .cpb-contact-rows {
        display: grid;
        gap: 10px;
    }

    .cpb-contact-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .cpb-contact-row .cpb-contact-label {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        color: #003366;
        font-size: 0.92rem;
    }

    .cpb-contact-row .cpb-contact-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        text-decoration: none;
        font-weight: 600;
    }

    .cpb-contact-row .cpb-contact-value:hover {
        text-decoration: underline;
    }

    .cpb-contact-row .cpb-contact-value.cpb-contact-whatsapp {
        color: #16a34a;
    }
    
    .cpb-buy-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #16a34a;
        color: #fff;
        padding: 15px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 0;
        transition: background 0.3s, transform 0.3s;
    }
    
    .cpb-buy-button:hover {
        background: #15803d;
        transform: translateY(-1px);
    }

    .cpb-store-actions {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        margin-top: 6px;
    }

    .cpb-store-actions .cpb-buy-button {
        width: 100%;
        max-width: 320px;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .cpb-products-grid {
            grid-template-columns: 1fr;
        }
        
        .cpb-contact-info {
            grid-template-columns: 1fr;
        }

        .cpb-store-info {
            grid-template-columns: 1fr;
            padding: 22px;
        }

        .cpb-address-box {
            align-self: stretch;
        }

        .cpb-gps-button {
            flex: 1 1 100%;
            width: 100%;
        }
        
        .cpb-store-image-container img {
            min-height: 300px;
        }

        .pa-widget.pa-w-casapublicadora .row {
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

        .pa-glide-casapublicadora .glide__slide {
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
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js" defer></script>
<script>
    // Carrossel Casa Publicadora
    const apiUrlCasaPublicadora = (window.APP_URL || '') + '/api/videos-casapublicadora';
    
    fetch(apiUrlCasaPublicadora)
        .then(response => response.json())
        .then(videos => {
            if (!videos || videos.length === 0 || videos.error) {
                console.error('Erro ao carregar vídeos da Casa Publicadora:', videos.error || 'Nenhum vídeo encontrado');
                return;
            }

            const slidesContainer = document.getElementById('casapublicadora-slides');
            const bulletsContainer = document.getElementById('casapublicadora-bullets');
            
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
            const casaPublicadoraCarousel = document.querySelector('.pa-glide-casapublicadora');
            if (casaPublicadoraCarousel && videos.length > 0) {
                const autoplay = casaPublicadoraCarousel.getAttribute('data-autoplay') || 2500;
                
                const glideCasaPublicadora = new Glide('.pa-glide-casapublicadora', {
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

                // Função para atualizar bullets ativos da Casa Publicadora
                function updateBulletsCasaPublicadora() {
                    const bullets = document.querySelectorAll('#casapublicadora-bullets i');
                    const totalSlides = bullets.length;
                    if (totalSlides === 0) return;
                    
                    let currentIndex = glideCasaPublicadora.index;
                    
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

                glideCasaPublicadora.mount();

                glideCasaPublicadora.on('mount.after', function() {
                    updateBulletsCasaPublicadora();
                    setTimeout(function() {
                        glideCasaPublicadora.update();
                        updateBulletsCasaPublicadora();
                    }, 100);
                });

                // Atualizar bullets após a conclusão da movimentação
                glideCasaPublicadora.on('run.after', updateBulletsCasaPublicadora);
                
                setTimeout(function() {
                    updateBulletsCasaPublicadora();
                }, 200);
            }
        })
        .catch(error => console.error('Erro ao carregar vídeos da Casa Publicadora:', error));
</script>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/cpb/header-cpb.webp') }}" alt="Casa Publicadora Brasileira" fetchpriority="high" decoding="async">

<div class="page-container">
    <div class="page-hero acb-fullbleed">
        <h1 class="page-title acb-title-serif">Casa Publicadora Brasileira</h1>
        <h2 class="page-subtitle">Bem-vindo(a) à seção da Casa Publicadora Brasileira, um espaço de inspiração e conhecimento para todos que desejam fortalecer a fé, a espiritualidade e os valores cristãos.</h2>
    </div>

    <!-- Carrossel de Vídeos Casa Publicadora -->
    <div class="page-section">
        <div class="pa-widget pa-w-casapublicadora py-4 col-12 position-relative bg-light mb-5">
            <div class="pa-slider-header mb-4">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <a 
                        href="https://www.youtube.com/@casapublicadora" 
                        target="_blank"
                        rel="noopener"
                        class="pa-logo-link"
                    >
                        <h3 style="margin: 0; color: #003366; font-family: 'Bebas neue', sans-serif; font-size: 1.8em;">Casa Publicadora</h3>
                    </a>
                    <a 
                        href="https://www.youtube.com/@casapublicadora" 
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
                    <div class="pa-glide-casapublicadora" 
                         data-autoplay="2500"
                         data-format="100">
                        <div class="glide__track" data-glide-el="track">
                            <div class="glide__slides" id="casapublicadora-slides">
                                <!-- Slides serão preenchidos via JavaScript -->
                            </div>
                        </div>
                        
                        <div class="pa-slider-controle d-flex align-items-center mt-4">
                            <div data-glide-el="controls">
                                <button type="button" class="pa-slider-btn" data-glide-dir="&lt;" aria-label="Anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </div>
                            <div class="mx-2 pa-slider-bullet" id="casapublicadora-bullets" data-glide-el="controls[nav]">
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

    <div class="page-section">
        <h2 class="page-section-title acb-title-serif">Principais Produtos da CPB</h2>
        <p class="page-text" style="text-align: center; max-width: 800px; margin-left: auto; margin-right: auto;">
            A CPB é sinônimo de qualidade e compromisso com a mensagem bíblica. Conheça alguns destaques:
        </p>

        <div class="cpb-products-grid">
            @foreach($products as $index => $category)
            <div class="cpb-product-card">
                @php
                    $iconMap = [
                        'book-open' => 'book',
                        'book' => 'journal-text',
                        'coffee' => 'cup-hot',
                        'users' => 'people'
                    ];
                    $iconClass = $iconMap[$category['icon']] ?? 'book';
                    
                    $colorClasses = ['amber', 'indigo', 'pink', 'cyan'];
                    $colorClass = $colorClasses[$index % count($colorClasses)];
                @endphp
                <div class="cpb-product-icon {{ $colorClass }}">
                    <i class="bi bi-{{ $iconClass }}"></i>
                </div>
                <h3>{{ $category['title'] }}</h3>
                <ul class="cpb-product-list">
                    @foreach($category['items'] as $item)
                    <li>
                        <span class="cpb-product-name">{{ $item['name'] }}</span>
                        <span class="cpb-product-desc">{{ $item['desc'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>

    <div class="page-section cpb-store-section">
        <div class="cpb-store-header">
            <div class="cpb-store-label">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Visite Nossa Loja Física</span>
            </div>
            <h2 class="page-section-title acb-title-serif" style="margin-bottom: 15px;">Brasília - DF</h2>
            <blockquote class="acb-quote" style="max-width: 800px; margin: 0 auto 30px;">
                <p>"Que tal levar para casa um pedacinho dessa inspiração? A Loja da CPB em Brasília está de portas abertas para você!"</p>
            </blockquote>
        </div>

        <div class="cpb-store-image-container">
            <img src="{{ asset('img/cpb/cpb_livraria.webp') }}" alt="Loja Física CPB Brasília" loading="lazy" decoding="async">
        </div>

        <div class="cpb-store-info">
            <div class="cpb-address-box">
                <strong><i class="bi bi-geo-alt-fill"></i> Endereço:</strong>
                <span>
                    Setor Comercial Norte Q 1 Bloco A Edifício Number One 17 e 23<br>
                    Asa Norte, Brasília - DF, 70711-900
                </span>
                <div class="cpb-gps-buttons">
                    <a href="https://www.waze.com/ul?q=Livraria+CPB+Brasilia"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="cpb-gps-button cpb-gps-waze">
                        <i class="bi bi-geo-alt"></i>
                        <span>Waze</span>
                    </a>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $addressEncoded }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="cpb-gps-button cpb-gps-maps">
                        <i class="bi bi-map"></i>
                        <span>Google Maps</span>
                    </a>
                </div>
            </div>

            <div class="cpb-contact-info">
                <div class="cpb-contact-item">
                    <strong><i class="bi bi-telephone"></i> Contato:</strong>
                    <div class="cpb-contact-rows">
                        <div class="cpb-contact-row">
                            <span class="cpb-contact-label">Telefone</span>
                            <a class="cpb-contact-value" href="tel:+556133212021">(61) 3321-2021</a>
                        </div>
                        <div class="cpb-contact-row">
                            <span class="cpb-contact-label">WhatsApp</span>
                            <a class="cpb-contact-value cpb-contact-whatsapp"
                               href="https://wa.me/5561982350008"
                               target="_blank"
                               rel="noopener noreferrer">
                                <i class="bi bi-whatsapp"></i>
                                <span>(61) 98235-0008</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="cpb-contact-item">
                    <strong><i class="bi bi-clock"></i> Horário:</strong>
                    <span style="font-size: 0.9rem;">
                        Segunda a Quinta: 08:30h às 18h<br>
                        Sexta: 08:30h às 16h
                    </span>
                </div>
            </div>

            <div class="cpb-store-actions">
                <a href="https://livrarias.cpb.com.br/brasilia"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="cpb-buy-button">
                    <i class="bi bi-cart3"></i>
                    <span>Comprar Online</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


