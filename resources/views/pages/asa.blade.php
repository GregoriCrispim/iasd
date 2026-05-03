@extends('layouts.app')

@section('title', 'IASD Central de Brasília - ASA (Ação Social Adventista)')

@section('meta-description', 'Conheça o trabalho da ASA - Ação Solidária Adventista da IASD Central de Brasília. Serviço comunitário, distribuição de alimentos e assistência social.')
@section('og-title', 'ASA - Ação Solidária Adventista')
@section('og-description', 'Servindo a comunidade com amor e compaixão. Conheça nossas ações sociais!')
@section('page-name', 'ASA')

@push('styles')
<style>

    .asa-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
        box-sizing: border-box;
    }

    .asa-header-wrap {
        width: 100%;
        overflow: hidden;
        aspect-ratio: 1920 / 300;
    }

    .asa-page-header-img {
        width: 100%;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .asa-intro {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 0;
        text-align: center;
    }

    .asa-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #fff;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .asa-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #f8f9fa;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .agencia-humanitaria {
        background: linear-gradient(135deg, #00632E 0%, #004D24 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 0 0 50px;
        text-align: center;
        color: #fff;
    }

    .agencia-humanitaria h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .agencia-humanitaria p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-conhecer {
        display: inline-block;
        background-color: #fff;
        color: #003366;
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-conhecer:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .estatisticas-section {
        margin: 60px 0;
    }

    .estatisticas-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .estatisticas-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .estatistica-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .estatistica-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .estatistica-numero {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3.5em;
        color: #003366;
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
    }

    .estatistica-label {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #666;
        font-weight: 500;
    }

    .campanhas-info {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        border-left: 5px solid #003366;
    }

    .campanhas-info h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .campanhas-info p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
    }

    .campanhas-info ul {
        margin: 12px 0 0;
        padding-left: 24px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        list-style: disc;
    }

    .campanhas-info li {
        margin: 6px 0;
        list-style-type: disc;
        position: static;
    }

    .como-ajudar-section {
        margin: 60px 0;
    }

    .como-ajudar-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .formas-ajuda-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .forma-ajuda-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .forma-ajuda-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }

    .forma-ajuda-card .emoji {
        font-size: 3em;
        margin-bottom: 15px;
        display: block;
    }

    .forma-ajuda-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .forma-ajuda-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
    }

    .btn-ajudar-grande {
        display: block;
        text-align: center;
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        padding: 18px 50px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.2em;
        margin: 40px auto;
        max-width: 400px;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-ajudar-grande:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .contato-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    .contato-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .contato-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 10px;
    }

    .contato-section a {
        color: #003366;
        text-decoration: none;
        font-weight: 600;
    }

    .contato-section a:hover {
        text-decoration: underline;
    }

    .galeria-section {
        margin: 60px 0;
    }

    .galeria-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .galeria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .galeria-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        aspect-ratio: 1;
        background: #f0f0f0;
    }

    .galeria-item:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .galeria-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .galeria-item img.lightbox-trigger {
        cursor: pointer;
    }

    .lightbox {
        --asa-lightbox-right-gap: 0px;
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: var(--header-height);
        width: calc(100vw - var(--asa-lightbox-right-gap));
        height: calc(100vh - var(--header-height));
        height: calc(100dvh - var(--header-height));
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;
        overflow: hidden;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: min(90%, 1200px);
        max-height: 90%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        margin: 0 auto;
    }

    .lightbox-close {
        position: absolute;
        top: 18px;
        right: 28px;
        color: #fff;
        font-size: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
        user-select: none;
        line-height: 1;
        z-index: 1;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.45);
    }

    .lightbox-close:hover {
        color: #3b82f6;
    }

    @media (max-width: 768px) {
        .asa-container {
            padding: 0 15px 20px;
        }

        .asa-intro {
            padding: 30px 20px;
        }

        .asa-intro h1 {
            font-size: 2.2em;
        }

        .agencia-humanitaria {
            padding: 40px 20px;
        }

        .agencia-humanitaria h2 {
            font-size: 2em;
        }

        .estatisticas-grid,
        .formas-ajuda-grid {
            grid-template-columns: 1fr;
        }

        .estatistica-numero {
            font-size: 2.5em;
        }

        .galeria-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="asa-header-wrap">
    <img class="asa-page-header-img" src="{{ asset('img/cards/asa/asa_header.webp') }}" alt="ASA (Ação Social Adventista)" fetchpriority="high" decoding="async">
</div>

<div class="asa-container">

    <!-- Seção Introdutória -->
    <div class="asa-intro acb-fullbleed">
        <h1>ASA (Ação Social Adventista)</h1>
        <p>
            O Ministério da Ação Social Adventista é uma expressão viva do amor de Cristo em ação. Ele não se limita a doar alimentos, roupas ou suprimentos; vai além, buscando ver o ser humano em sua totalidade. Este ministério entende que as necessidades das pessoas vão muito além do material, abrangendo o emocional, o espiritual e o social.
        </p>
        <p>
            Cada ação realizada é um gesto de carinho e cuidado, revelando a preocupação genuína com o próximo. Seja através de seminários educativos, programas de desenvolvimento comunitário, visitas, aconselhamento, ou outras iniciativas, o Ministério da Ação Social Adventista se dedica a transformar vidas e a trazer esperança.
        </p>
    </div>

    <!-- Seção de Estatísticas -->
    <div class="estatisticas-section">
        <h2 class="acb-title-serif">Estes são alguns dos números que a ASA alcançou (entre Setembro/2025 e Março/2026):</h2>

        <div class="estatisticas-grid">
            <div class="estatistica-card">
                <span class="estatistica-numero" data-value="250">0</span>
                <div class="estatistica-label">Assistências familiares</div>
            </div>

            <div class="estatistica-card">
                <span class="estatistica-numero" data-value="800">0</span>
                <div class="estatistica-label">Cestas básicas entregues</div>
            </div>

            <div class="estatistica-card">
                <span class="estatistica-numero" data-value="3200">0</span>
                <div class="estatistica-label">Apoio a necessidades básicas</div>
            </div>

            <div class="estatistica-card">
                <span class="estatistica-numero" data-value="400">0</span>
                <div class="estatistica-label">Doações<br><strong>(móveis, utensílios domésticos)</strong></div>
            </div>

            <div class="estatistica-card">
                <span class="estatistica-numero" data-value="50">0</span>
                <div class="estatistica-label">Pessoas atendidas em<br><strong>cursos de capacitação</strong></div>
            </div>
        </div>

        <div class="campanhas-info">
            <h3>Campanhas e Arrecadações</h3>
            <p>Algumas das campanhas e arrecadações realizadas:</p>
            <ul>
                <li>Projeto Aqueça um Irmão</li>
                <li>Projeto Meias do bem</li>
                <li>Projeto Cestas que Alimentam</li>
                <li>Mutirão de Natal</li>
            </ul>
        </div>
    </div>

    <!-- Seção Como Você Pode Ajudar -->
    <div class="como-ajudar-section">
        <h2 class="acb-title-serif">Como Você Pode Ajudar</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto;">
            A ASA depende de voluntários e doações para continuar seu trabalho. Você pode contribuir de várias formas:
        </p>

        <div class="formas-ajuda-grid">
            <div class="forma-ajuda-card">
                <i class="bi bi-gift emoji"></i>
                <h4>Doações materiais</h4>
                <p>Alimentos não perecíveis, roupas, cobertores, produtos de higiene</p>
            </div>

            <div class="forma-ajuda-card">
                <i class="bi bi-cash-coin emoji"></i>
                <h4>Doações financeiras</h4>
                <p>Recursos para manutenção dos projetos</p>
            </div>

            <div class="forma-ajuda-card">
                <i class="bi bi-clock emoji"></i>
                <h4>Trabalho voluntário</h4>
                <p>Seu tempo e talento fazendo a diferença</p>
            </div>

            <div class="forma-ajuda-card">
                <i class="bi bi-megaphone emoji"></i>
                <h4>Divulgação</h4>
                <p>Compartilhando nosso trabalho em suas redes</p>
            </div>
        </div>

        <div id="ajudar-container">
            <a href="#" id="btn-ajudar" class="btn-ajudar-grande">QUERO AJUDAR AGORA!</a>
            <div id="contato-info" style="display: none; text-align: center; padding: 30px; background: #f8f9fa; border-radius: 10px; max-width: 500px; margin: 0 auto;">
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.8em; color: #003366; margin-bottom: 20px; font-weight: 500;">Contato ASA</h3>
                <p style="font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #333;">
                    <strong><i class="bi bi-envelope"></i> Email:</strong> <a href="mailto:asacentralbsb@gmail.com" style="color: #003366; text-decoration: none; font-weight: 600;">asacentralbsb@gmail.com</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Seção Galeria de Fotos -->
    <div class="galeria-section">
        <h2 class="acb-title-serif">Galeria de Fotos</h2>

        <div class="galeria-grid">
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio1.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio1.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio2.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio2.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio3.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio3.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio4.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio4.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio5.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio5.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio6.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio6.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio7.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio7.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio8.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio8.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/apoio9.webp') }}" alt="ASA - Apoio" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/apoio9.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/bazar1.webp') }}" alt="ASA - Bazar" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/bazar1.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/costura1.webp') }}" alt="ASA - Costura" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/costura1.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/costura2.webp') }}" alt="ASA - Costura" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/costura2.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/costura3.webp') }}" alt="ASA - Costura" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/costura3.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/culinaria1.webp') }}" alt="ASA - Culinária" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/culinaria1.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/culinaria2.webp') }}" alt="ASA - Culinária" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/culinaria2.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/culinaria3.webp') }}" alt="ASA - Culinária" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/culinaria3.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/pizzas1.webp') }}" alt="ASA - Pizzas" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/pizzas1.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/tesouras2.webp') }}" alt="ASA - Tesouras" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/tesouras2.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/tesouras3.webp') }}" alt="ASA - Tesouras" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/tesouras3.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/tesouras4.webp') }}" alt="ASA - Tesouras" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/tesouras4.webp') }}">
            </div>
            <div class="galeria-item">
                <img src="{{ asset('img/cards/asa/tesouras5.webp') }}" alt="ASA - Tesouras" loading="lazy" decoding="async" class="lightbox-trigger" data-full="{{ asset('img/cards/asa/tesouras5.webp') }}">
            </div>
        </div>
    </div>

    <div class="lightbox" id="lightbox" aria-hidden="true">
        <span class="lightbox-close" aria-label="Fechar">&times;</span>
        <img class="lightbox-content" id="lightbox-img" src="" alt="" loading="lazy" decoding="async">
    </div>

    <!-- Seção de Contato -->
    <div class="contato-section acb-fullbleed">
        <h3 class="acb-title-serif">Contato ASA</h3>
        <p>
            <strong><i class="bi bi-envelope"></i> Email:</strong> <a href="mailto:asacentralbsb@gmail.com">asacentralbsb@gmail.com</a>
        </p>
        <blockquote class="acb-quote" style="max-width: 900px; margin: 22px auto 0;">
            <p>
                "Porque tive fome, e me destes de comer; tive sede, e me destes de beber; era estrangeiro, e me hospedastes; estava nu, e me vestistes; adoeci, e me visitastes; estava na prisão, e fostes ver-me."
            </p>
            <span class="acb-quote__ref"><i class="bi bi-book-half"></i> Mateus 25:35-36</span>
        </blockquote>
    </div>

    <!-- Seção Manual ASA -->
    <div class="contato-section" style="background: linear-gradient(135deg, #003366 0%, #1b4472 100%); border-top: 3px solid #f1c9a1;">
        <h3 style="color: #fff;">Mais Informações</h3>
        <p style="color: #f8f9fa; font-size: 1.1rem;">
            Para mais informações, acesse o <strong>"Manual da ASA"</strong>
        </p>
        <a href="https://drive.google.com/file/d/1ACGngpGUeDuVU_LE3X3vDeaEX7clkoHN/view?usp=sharing" target="_blank" style="display: inline-block; margin-top: 15px; background-color: #f1c9a1; color: #003366; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1em; transition: transform 0.3s, box-shadow 0.3s, background 0.3s; font-family: 'Roboto', sans-serif;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.3)'; this.style.backgroundColor='#e7ac6d';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.backgroundColor='#f1c9a1';">Acessar Manual</a>
    </div>

    <!-- Seção Agência Humanitária -->
    <div class="agencia-humanitaria acb-fullbleed">
        <h2 class="acb-title-serif">CONHEÇA A AGÊNCIA HUMANITÁRIA DA IGREJA ADVENTISTA DO SÉTIMO DIA</h2>
        <img src="{{ asset('img/cards/asa/ADRA_Horizontal_Logo.webp') }}" alt="ADRA Logo" style="max-width: 300px; margin: 20px auto 30px; display: block;">
        <p>
            A Agência Humanitária da Igreja Adventista do Sétimo Dia trabalha em conjunto com a ASA para promover ações de solidariedade e assistência humanitária em todo o mundo, seguindo os princípios cristãos de amor ao próximo e serviço desinteressado.
        </p>
        <a href="https://adra.org.br/" target="_blank" class="btn-conhecer">Conheça</a>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const estatisticasSection = document.querySelector('.estatisticas-section');
    const numeros = document.querySelectorAll('.estatistica-numero');
    let animado = false;

    function formatarNumero(num) {
        return Math.floor(num).toLocaleString('pt-BR');
    }

    function animarContagem(elemento, valorFinal) {
        const duracao = 2000;
        const incremento = valorFinal / (duracao / 16);
        let valorAtual = 0;

        const timer = setInterval(() => {
            valorAtual += incremento;

            if (valorAtual >= valorFinal) {
                valorAtual = valorFinal;
                clearInterval(timer);
            }

            elemento.textContent = formatarNumero(valorAtual);
        }, 16);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !animado) {
                animado = true;

                numeros.forEach(numero => {
                    const valorFinal = parseFloat(numero.getAttribute('data-value'));
                    animarContagem(numero, valorFinal);
                });
            }
        });
    }, {
        threshold: 0.3
    });

    if (estatisticasSection) {
        observer.observe(estatisticasSection);
    }

    const btnAjudar = document.getElementById('btn-ajudar');
    const contatoInfo = document.getElementById('contato-info');

    if (btnAjudar && contatoInfo) {
        btnAjudar.addEventListener('click', function(e) {
            e.preventDefault();
            btnAjudar.style.display = 'none';
            contatoInfo.style.display = 'block';
        });
    }

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxTriggers = document.querySelectorAll('.lightbox-trigger');
    const pageAside = document.querySelector('aside');

    function syncLightboxBounds() {
        if (!lightbox) return;

        let rightGap = 0;

        if (pageAside) {
            const asideRect = pageAside.getBoundingClientRect();

            if (asideRect.width > 0 && asideRect.right > asideRect.left) {
                rightGap = Math.max(0, asideRect.width);
            }
        }

        lightbox.style.setProperty('--asa-lightbox-right-gap', `${rightGap}px`);
    }

    function openLightbox() {
        if (!lightbox) return;
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        syncLightboxBounds();
        lightbox.classList.add('active');
        lightbox.setAttribute('aria-hidden', 'false');
    }

    function closeLightbox() {
        if (!lightbox || !lightboxImg) return;
        lightbox.classList.remove('active');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImg.src = '';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }

    if (lightbox && lightboxImg && lightboxClose && lightboxTriggers.length > 0) {
        syncLightboxBounds();
        window.addEventListener('resize', syncLightboxBounds);

        lightboxTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                syncLightboxBounds();
                const fullSizeSrc = this.getAttribute('data-full') || this.getAttribute('src');
                const altText = this.getAttribute('alt') || '';
                lightboxImg.src = fullSizeSrc || '';
                lightboxImg.alt = altText;
                openLightbox();
            });
        });

        lightboxClose.addEventListener('click', function() {
            closeLightbox();
        });

        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                closeLightbox();
            }
        });
    }
});
</script>
@endpush
