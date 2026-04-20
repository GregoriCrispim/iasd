@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Filmes e Séries')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.theme.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

<style>

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

    .btn-carousel {
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

    .btn-carousel-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
    }

    .btn-carousel-primary:hover {
        background-color: #001531;
        border-color: #001531;
    }

    /* Estilos do carrossel Feliz7Play */
    .pa-widget.pa-w-feliz7play {
        padding: 2rem 0;
        position: relative;
        background-color: #f8f9fa;
        margin-bottom: 3rem;
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }

    .pa-widget.pa-w-feliz7play .row {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        overflow: hidden;
    }

    .pa-widget.pa-w-feliz7play .col {
        overflow: hidden;
        width: 100%;
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

    .pa-f7p-logo {
        max-height: 40px;
        width: auto;
        height: auto;
        display: block;
    }

    .pa-slider-header .btn-carousel-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .pa-slider-header .btn-carousel-primary:hover {
        background-color: #001531;
        border-color: #001531;
    }

    /* Estilos do Glide */
    .pa-glide-feliz7play {
        position: relative;
        width: 100%;
    }

    .pa-glide-feliz7play .glide__track {
        overflow: hidden;
        width: 100%;
    }

    .pa-glide-feliz7play .glide__slides {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pa-glide-feliz7play .glide__slide {
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

    .pa-glide-feliz7play .glide__slide:hover .carousel-image-link img {
        transform: scale(1.05);
    }

    .pa-slider-controle {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1.5rem;
        padding: 0 1rem;
        gap: 1rem;
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

    .filmes-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .header-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .header-section h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .header-section .subtitle {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #003366;
        margin-bottom: 25px;
        display: block;
    }

    .header-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .section-title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .section-benefits {
        margin: 60px 0;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .benefit-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 35px 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .benefit-icon {
        width: 70px;
        height: 70px;
        background: #f8f9fa;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2em;
        transition: transform 0.3s, background 0.3s;
    }

    .benefit-card:hover .benefit-icon {
        transform: scale(1.1);
        background: #e3f2fd;
    }

    .benefit-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .benefit-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #666;
        line-height: 1.7;
    }

    .destaque-section-wrapper {
        margin: 60px 0;
    }

    .destaque-card {
        background: #1e293b;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        border: 1px solid #334155;
    }

    .destaque-img-container {
        width: 100%;
        height: 400px;
        position: relative;
        overflow: hidden;
    }

    .destaque-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .destaque-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(to top, #1e293b, transparent);
    }

    .destaque-content {
        padding: 50px 40px;
        text-align: center;
        color: #fff;
    }

    .badge {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 50px;
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        font-family: 'Roboto', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(37, 99, 235, 0.3);
        margin-bottom: 20px;
    }

    .destaque-content h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .destaque-content p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #cbd5e1;
        max-width: 700px;
        margin: 0 auto 30px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #2563eb;
        color: #fff;
        padding: 16px 45px;
        border-radius: 12px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        font-size: 1.1em;
        transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
    }

    .btn-primary:hover {
        background: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.5);
    }

    .destaque-meta {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 25px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #94a3b8;
    }

    .destaque-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .destaque-meta .separator {
        width: 4px;
        height: 4px;
        background: #475569;
        border-radius: 50%;
    }

    .destaques-section {
        margin: 60px 0;
    }

    .destaques-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
    }

    .destaque-item {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .destaque-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .destaque-item .icon-box {
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

    .destaque-item:hover .icon-box {
        transform: scale(1.1);
    }

    .destaque-item .icon-box.amber {
        background: #fef3c7;
    }

    .destaque-item .icon-box.indigo {
        background: #e0e7ff;
    }

    .destaque-item .icon-box.pink {
        background: #fce7f3;
    }

    .destaque-item .icon-box.cyan {
        background: #cffafe;
    }

    .destaque-item h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .destaque-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }

    .footer-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        border-left: 5px solid #003366;
    }

    .footer-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .footer-section h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .footer-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 10px;
    }

    .btn-youtube {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: #dc2626;
        color: #fff;
        padding: 14px 35px;
        border-radius: 10px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
        box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
    }

    .btn-youtube:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(220, 38, 38, 0.5);
    }

    .footer-divider {
        width: 100%;
        height: 1px;
        background: #e0e0e0;
        margin: 40px 0;
    }

    .verse-box {
        background: #fff;
        padding: 35px;
        border-radius: 15px;
        border: 2px solid #e0e0e0;
        max-width: 700px;
        margin: 30px auto 0;
    }

    .verse-box p {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .verse-box .reference {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #003366;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .filmes-container {
            padding: 20px 15px;
        }

        .header-section {
            padding: 30px 20px;
        }

        .header-section h1 {
            font-size: 2.2em;
        }

        .header-section .subtitle {
            font-size: 1.3em;
        }

        .section-title {
            font-size: 2em;
        }

        .benefits-grid,
        .destaques-grid {
            grid-template-columns: 1fr;
        }

        .destaque-img-container {
            height: 300px;
        }

        .destaque-content {
            padding: 30px 20px;
        }

        .destaque-content h2 {
            font-size: 2.5em;
        }

        .btn-primary,
        .btn-youtube {
            width: 100%;
        }

        .verse-box p {
            font-size: 1.4em;
        }

        .pa-widget.pa-w-feliz7play .row {
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
            gap: 12px;
            text-align: center;
        }

        .btn-carousel.btn-carousel-primary {
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 0.95rem;
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

        .pa-glide-feliz7play .glide__slide {
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

@section('content')
<img src="{{ asset('img/filmes_series/filmes_series_header.webp') }}" alt="Filmes e Séries" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="filmes-container">
    <!-- SEÇÃO DE CABEÇALHO -->
    <div class="header-section acb-fullbleed">
        <h1 class="acb-title-serif">Filmes e Séries</h1>
        <span class="subtitle">Descubra Inspiração Divina na Tela!</span>
        <p>
            Bem-vindo(a) à sua janela espiritual para filmes e séries que celebram histórias bíblicas, valores cristãos e lições de fé!
            Aqui, você encontra produções cuidadosamente selecionadas para edificar sua família, fortalecer sua comunhão com Deus e mergulhar em narrativas que refletem a verdade eterna.
        </p>
    </div>

    <!-- SEÇÃO 1: POR QUE ASSISTIR? -->
    <div class="section-benefits">
        <h2 class="section-title acb-title-serif">Por Que Assistir Filmes e Séries Bíblicas?</h2>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon"><i class="bi bi-heart-fill"></i></div>
                <h3>Crescimento Espiritual</h3>
                <p>Conecte-se com histórias que inspiram reflexão e renovam sua fé.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon"><i class="bi bi-shield-check"></i></div>
                <h3>Para Toda a Família</h3>
                <p>Conteúdo seguro e educativo para crianças, jovens e adultos.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon"><i class="bi bi-book-half"></i></div>
                <h3>Aprendizado Histórico</h3>
                <p>Explore cenários, costumes e personagens das Escrituras de forma dinâmica.</p>
            </div>
        </div>
    </div>

    <!-- SEÇÃO DE DESTAQUE VERTICAL - O GAROTO DO LENÇO -->
    <div class="destaque-section-wrapper">
        <div class="destaque-card">
            <!-- Imagem no Topo -->
            <div class="destaque-img-container">
                <img id="filme-destaque-img"
                     src="{{ asset('img/garoto_lenco.jpg') }}"
                     alt="Pôster do filme O Garoto do Lenço"
                     loading="lazy" decoding="async"
                     onerror="this.src='https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1200&auto=format&fit=crop'" />
                <div class="destaque-overlay"></div>
            </div>

            <!-- Conteúdo Abaixo -->
            <div class="destaque-content">
                <span class="badge">Filme em Destaque</span>
                <h2 class="acb-title-serif">O GAROTO DO LENÇO</h2>
                <p>
                    Uma história poderosa sobre superação, o valor da amizade e o impacto transformador da fé em momentos de desafio. Uma produção emocionante que fala diretamente ao coração.
                </p>

                <a href="https://www.youtube.com/watch?v=BAJGGhU3dzo" target="_blank" rel="noopener noreferrer" class="btn-primary">
                    ▶ Assistir no YouTube
                </a>

                <div class="destaque-meta">
                    <span><i class="bi bi-tv"></i> Livre para todos os públicos</span>
                    <span class="separator"></span>
                    <span><i class="bi bi-film"></i> Drama / Inspiração</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEÇÃO 2: DESTAQUES IMPERDÍVEIS -->
    <div class="destaques-section">
        <h2 class="section-title acb-title-serif">Destaques Imperdíveis</h2>

        <div class="destaques-grid">
            <div class="destaque-item">
                <div class="icon-box amber"><i class="bi bi-star-fill"></i></div>
                <h4>Dramas Épicos</h4>
                <p>Reviva o Êxodo, a jornada de Davi ou a coragem de Ester com produções de alta qualidade!</p>
            </div>

            <div class="destaque-item">
                <div class="icon-box indigo"><i class="bi bi-film"></i></div>
                <h4>Documentários</h4>
                <p>Entenda o contexto arqueológico e cultural por trás das passagens bíblicas.</p>
            </div>

            <div class="destaque-item">
                <div class="icon-box pink"><i class="bi bi-people-fill"></i></div>
                <h4>Animação Infantil</h4>
                <p>Ensine os pequenos sobre amor, obediência e milagres com séries coloridas e divertidas!</p>
            </div>

            <div class="destaque-item">
                <div class="icon-box cyan"><i class="bi bi-journals"></i></div>
                <h4>Estudos Bíblicos</h4>
                <p>Aprofunde-se em temas como profecia, santidade e o plano da salvação.</p>
            </div>
        </div>
    </div>

    <!-- Carrossel de Mídias Feliz 7 Play -->
    <div class="pa-widget pa-w-feliz7play py-4 col-12 position-relative bg-light mb-5">
        <div class="pa-slider-header mb-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a 
                    href="https://feliz7play.com" 
                    target="_blank"
                    rel="noopener"
                    class="pa-logo-link"
                >
                    <img 
                        src="{{ asset('img/midias/imgi_5_f7p-logo.svg') }}" 
                        alt="Feliz7Play" 
                        class="pa-f7p-logo"
                        loading="lazy" decoding="async"
                    />
                </a>
                <a 
                    href="https://feliz7play.com" 
                    target="_blank"
                    class="btn-carousel btn-carousel-primary"
                    rel="noopener"
                >
                    Acessar o site
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <div class="pa-glide-feliz7play" 
                     data-autoplay="2500"
                     data-format="100">
                    <div class="glide__track" data-glide-el="track">
                        <div class="glide__slides">
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/provai-e-vede/provai-e-vede-2025?target=bencaos-sem-medida" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2025/01/Miniatura_ProvaieVede_2025_Eps6.jpg" 
                                        alt="Bençãos sem medida" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/provai-e-vede-libras/provai-e-vede-2024-libras?target=iniciativa-de-alimentacao-libras" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/12/Miniatura_ProvaieVede2024_Libras_Eps52.jpg" 
                                        alt="Iniciativa de alimentação - Libras" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/documentario-o-legado" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/maxresdefault-24.jpg" 
                                        alt="Documentário O Legado" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/inverso?target=livre-estou" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/Thumb_IN_episodio14.jpg" 
                                        alt="LIVRE ESTOU" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/roller-peps/roller-peps-1?target=a-lesma-atleta" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/A-Lesma-Atleta.jpg" 
                                        alt="A Lesma Atleta" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/corrida-da-fe?target=veloz" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/veloz.jpg" 
                                        alt="Veloz" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/c/2359-ate-o-ultimo-minuto/2359-ate-o-ultimo-minuto-5?target=sob-pressao" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/ep2-2359.jpg" 
                                        alt="Sob Pressão" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                            <div class="glide__slide">
                                <a href="https://feliz7play.com/pt/maratona-tuis-3" target="_blank" class="carousel-image-link">
                                    <img 
                                        src="https://files.adventistas.org/feliz7play/v2/sites/2/2024/11/thumb-tuis.jpg" 
                                        alt="Maratona Tuis 3" 
                                        loading="lazy" decoding="async"
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pa-slider-controle d-flex align-items-center mt-4">
                        <div data-glide-el="controls">
                            <button type="button" class="pa-slider-btn" data-glide-dir="&lt;" aria-label="Anterior">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </div>
                        <div class="mx-2 pa-slider-bullet" data-glide-el="controls[nav]">
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=0"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=1"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=2"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=3"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=4"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=5"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=6"></i>
                            <i class="fas fa-circle fa-xs mx-1" data-glide-dir="=7"></i>
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

    <!-- RODAPÉ E MENSAGEM FINAL -->
    <div class="footer-section acb-fullbleed">
        <h3 class="acb-title-serif">Não Perca Nenhum Lançamento!</h3>
        <p>Siga nossas redes sociais e ative as notificações para ser avisado(a) sobre novas produções!</p>
        <a href="https://www.youtube.com/@feliz7play" target="_blank" rel="noopener noreferrer" class="btn-youtube">
            ▶ Inscrever-se no YouTube
        </a>

        <div class="footer-divider"></div>

        <h4 class="acb-title-serif">Uma Mensagem Final</h4>
        <p style="font-style: italic; max-width: 650px; margin: 0 auto;">
            Que cada filme ou série assistido aqui seja uma semente plantada em seu coração, frutificando em amor, esperança e comunhão com Deus.
        </p>

        <div class="verse-box">
            <blockquote class="acb-quote" style="max-width: 700px; margin: 0 auto;">
                <p>"Tudo o que é verdadeiro, tudo o que é respeitável [...] é isso que devem pensar!"</p>
                <span class="acb-quote__ref">— Filipenses 4:8 (NVT)</span>
            </blockquote>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script para gerenciar a imagem do filme (fallback se a imagem não existir)
    const img = document.getElementById('filme-destaque-img');
    if (img) {
        img.onerror = function() {
            this.src = 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1200&auto=format&fit=crop';
        };
    }

    // Carrossel Feliz 7 Play
    const carouselElement = document.querySelector('.pa-glide-feliz7play');
    if (carouselElement) {
        const autoplay = carouselElement.getAttribute('data-autoplay') || 2500;
        
        const glide = new Glide('.pa-glide-feliz7play', {
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

        // Função para atualizar bullets ativos
        function updateBullets() {
            const bullets = document.querySelectorAll('.pa-slider-bullet i');
            const totalSlides = bullets.length;
            if (totalSlides === 0) return;
            let currentIndex = glide.index;
            
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

        glide.mount();

        // Garantir que o Glide recalcule após o mount
        glide.on('mount.after', function() {
            // Atualizar bullets imediatamente após o mount
            updateBullets();
            
            // Forçar recálculo das dimensões
            setTimeout(function() {
                glide.update();
                updateBullets();
            }, 100);
        });

        // Prevenir recarregamento visível no loop infinito
        glide.on('run', function() {
            // O Glide com type: 'carousel' e rewind: true já cria loop infinito
            // Este handler garante transições suaves
        });

        // Atualizar bullets ativos quando o carrossel se move
        glide.on('run', updateBullets);
        
        // Garantir que o primeiro bullet esteja ativo na inicialização
        setTimeout(function() {
            updateBullets();
        }, 200);
    }
});
</script>
@endpush
