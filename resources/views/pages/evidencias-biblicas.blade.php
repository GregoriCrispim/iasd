@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Evidências Bíblicas')

@section('meta-description', 'Explore as evidências históricas e arqueológicas que confirmam a Bíblia. Estudos profundos que fortalecem sua fé na IASD Central de Brasília.')
@section('og-title', 'Evidências Bíblicas - IASD Central de Brasília')
@section('og-description', 'A Bíblia é confiável! Conheça as evidências que comprovam sua veracidade histórica.')
@section('page-name', 'Evidências Bíblicas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=Roboto:wght@300;400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">

<style>
    html { scroll-behavior: smooth; }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #fcfaf7;
        color: #2d2a26;
    }

    h1, h2, h3 {
        font-family: 'Crimson Pro', serif;
    }

    .evidencias-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .intro-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .intro-section h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .intro-section .subtitle {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #003366;
        margin-bottom: 20px;
        display: block;
    }

    .intro-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        max-width: 900px;
        margin: 0 auto;
    }

    .section-block {
        margin-top: 60px;
        margin-bottom: 50px;
    }

    .section-title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .section-intro {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #57534e;
        margin-bottom: 30px;
        line-height: 1.8;
        font-style: italic;
        text-align: center;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 40px;
    }

    .evidence-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .evidence-card:nth-child(1) {
        border-left: 4px solid #f59e0b;
    }

    .evidence-card:nth-child(n+2) {
        border-left: 4px solid #a8a29e;
    }

    .evidence-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .evidence-card h3 {
        font-family: 'Crimson Pro', serif;
        font-size: 1.3em;
        color: #292524;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .evidence-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        line-height: 1.7;
        color: #44403c;
    }

    .jesus-section {
        background: #292524;
        color: #fff;
        border-radius: 15px;
        padding: 50px 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        text-align: center;
    }

    .jesus-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fbbf24;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .jesus-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .jesus-item h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9em;
        color: #fcd34d;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .jesus-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        line-height: 1.7;
        color: #d6d3d1;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #f59e0b;
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
    }

    .stat-label {
        font-family: 'Roboto', sans-serif;
        font-size: 0.85em;
        color: #292524;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stat-desc {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #57534e;
    }

    .explore-section {
        margin: 60px 0;
    }

    .explore-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .mab-banner {
        background: #292524;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        border: 2px solid rgba(245, 158, 11, 0.3);
        margin-bottom: 30px;
    }

    .mab-image {
        width: 100%;
        height: 500px;
        background: #44403c;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .mab-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mab-content {
        background: #1c1917;
        color: #fff;
        padding: 50px 40px;
        text-align: center;
    }

    .mab-badge {
        background: #f59e0b;
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.75em;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-bottom: 20px;
    }

    .mab-content h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fbbf24;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .mab-content p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #d6d3d1;
        margin-bottom: 30px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-mab {
        display: inline-block;
        background: #f59e0b;
        color: #fff;
        padding: 14px 35px;
        border-radius: 10px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        transition: background 0.3s, transform 0.3s;
    }

    .btn-mab:hover {
        background: #fbbf24;
        transform: scale(1.05);
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .link-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #fff;
        border: 2px solid #e7e5e4;
        border-radius: 15px;
        padding: 25px;
        text-decoration: none;
        transition: background 0.3s, border-color 0.3s;
    }

    .link-card:hover {
        background: #fffbeb;
        border-color: #f59e0b;
    }

    .link-emoji {
        font-size: 2.5em;
    }

    .link-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1em;
        color: #292524;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .link-card:hover h4 {
        color: #b45309;
    }

    .link-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        color: #78716c;
        font-style: italic;
    }

    .quote-section {
        text-align: center;
        padding: 50px 20px;
        border-top: 2px solid #e7e5e4;
        margin-top: 50px;
    }

    .quote-text {
        font-family: 'Crimson Pro', serif;
        font-size: 1.5em;
        color: #44403c;
        line-height: 1.8;
        font-style: italic;
        max-width: 900px;
        margin: 0 auto;
    }

    .quote-ref {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #92400e;
        font-weight: 700;
        display: block;
        margin-top: 15px;
    }

    @media (max-width: 1200px) {
        .cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .evidencias-container {
            padding: 20px 15px;
        }

        .intro-section {
            padding: 30px 20px;
        }

        .intro-section h1 {
            font-size: 2.2em;
        }

        .section-title {
            font-size: 2em;
        }

        .cards-grid,
        .jesus-grid,
        .stats-grid,
        .links-grid {
            grid-template-columns: 1fr;
        }

        .jesus-section,
        .mab-content {
            padding: 30px 20px;
        }

        .mab-image {
            height: 320px;
        }

        .quote-text {
            font-size: 1.2em;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/evidencias_biblicas/evidencias_biblicas_header.webp') }}" alt="Evidências Bíblicas" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="evidencias-container">
    <!-- Seção Introdutória -->
    <div class="intro-section acb-fullbleed">
        <h1>Evidências Bíblicas</h1>
        <span class="subtitle">Arqueologia, História e Fé</span>
        <p>
            Uma jornada pelas descobertas que confirmam a veracidade das Escrituras Sagradas.
        </p>
    </div>

    <!-- Secção 1: Arqueologia e a Bíblia -->
    <div class="section-block">
        <h2 class="section-title acb-title-serif">Arqueologia e a Bíblia</h2>
        <p class="section-intro">
            A Bíblia não é apenas um livro de fé, mas também um registro histórico comprovado por descobertas arqueológicas.
        </p>

        <div class="cards-grid">
            <div class="evidence-card">
                <h3>Manuscritos do Mar Morto (1947)</h3>
                <p>Encontrados em cavernas próximas ao Mar Morto, incluem cópias de livros do Antigo Testamento datadas de 200 a.C.</p>
            </div>
            <div class="evidence-card">
                <h3>Anel de Pôncio Pilatos (2018)</h3>
                <p>Um anel de bronze identificado em Israel associado ao governador que ordenou a crucificação de Jesus.</p>
            </div>
            <div class="evidence-card">
                <h3>Cidade de Zanoa (3.200 anos)</h3>
                <p>Escavações revelaram muros e cerâmicas corroborando relatos de cidades israelitas em Josué e Neemias.</p>
            </div>
            <div class="evidence-card">
                <h3>Fosso de Salomão em Jerusalém</h3>
                <p>Um fosso monumental datado da Idade do Ferro, alinhando-se com as fortificações descritas em 1 Reis 11:27.</p>
            </div>
        </div>
    </div>

    <!-- Secção 2: Jesus Cristo -->
    <div class="jesus-section acb-fullbleed">
        <h2 class="acb-title-serif">Jesus Cristo: Evidências Históricas</h2>
        <div class="jesus-grid">
            <div class="jesus-item">
                <h3>Confiabilidade dos Evangelhos</h3>
                <p>Relatos escritos por testemunhas oculares que se harmonizam em pontos centrais.</p>
            </div>
            <div class="jesus-item">
                <h3>Fontes Seculares</h3>
                <p>Tácito e Flávio Josefo mencionam Jesus e o impacto do cristianismo primitivo.</p>
            </div>
            <div class="jesus-item">
                <h3>Túmulo Vazio</h3>
                <p>O papel das mulheres como testemunhas reforça a autenticidade do relato histórico.</p>
            </div>
            <div class="jesus-item">
                <h3>Transformação dos Discípulos</h3>
                <p>A coragem e martírio dos apóstolos sugerem uma convicção inabalável.</p>
            </div>
        </div>
    </div>

    <!-- Secção 3: Integridade Textual -->
    <div class="section-block">
        <h2 class="section-title acb-title-serif">Integridade Textual</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number">1.000+</span>
                <p class="stat-label">Anos de Preservação</p>
                <p class="stat-desc">O texto permaneceu praticamente inalterado.</p>
            </div>
            <div class="stat-card">
                <span class="stat-number">40</span>
                <p class="stat-label">Autores Diferentes</p>
                <p class="stat-desc">Mensagem coerente em 3 continentes.</p>
            </div>
            <div class="stat-card">
                <span class="stat-number">Gezer</span>
                <p class="stat-label">Portão de Salomão</p>
                <p class="stat-desc">Confirmação de projetos monumentais (1 Reis).</p>
            </div>
        </div>
    </div>

    <!-- Secção Explore Mais -->
    <div class="explore-section">
        <h2 class="acb-title-serif">Explore e Aprofunde-se:</h2>

        <div class="mab-banner acb-fullbleed">
            <!-- Imagem em destaque -->
            <div class="mab-image">
                <img src="{{ asset('img/evidencias_biblicas/imgi_91_stock-315-scaled.webp') }}"
                     alt="Museu de Arqueologia Bíblica"
                     loading="lazy" decoding="async"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'p-16 text-center\'><div class=\'text-6xl mb-4\'>🏛️</div><p class=\'text-amber-200 font-serif italic text-xl\'>Acervo Histórico MAB</p></div>'">
            </div>

            <!-- Conteúdo do MAB -->
            <div class="mab-content">
                <span class="mab-badge">Destaque Especial</span>
                <h3>Museu de Arqueologia Bíblica (MAB)</h3>
                <p>
                    Localizado no campus do UNASP (Estado de São Paulo), o MAB é referência na América Latina. Explore artefatos da época de Jesus e réplicas de manuscritos que narram milênios de história bíblica.
                </p>
                <a href="http://mab.unasp.edu.br" target="_blank" class="btn-mab">
                    Conhecer o Museu
                </a>
            </div>
        </div>

        <!-- Outros Links -->
        <div class="links-grid">
            <a href="https://www.novotempo.com/programa/evidencias/" target="_blank" class="link-card">
                <i class="bi bi-tv link-emoji"></i>
                <div>
                    <h4>Série Evidências</h4>
                    <p>Assista na TV Novo Tempo.</p>
                </div>
            </a>
            <div class="link-card">
                <i class="bi bi-globe2 link-emoji"></i>
                <div>
                    <h4>Museus Internacionais</h4>
                    <p>Louvre (França) e Museu de Israel.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Citação Final -->
    <div class="quote-section acb-fullbleed">
        <blockquote class="acb-quote" style="max-width: 900px; margin: 0 auto;">
            <p>"Estejam sempre preparados para responder a qualquer que lhes pedir a razão da esperança que há em vocês."</p>
            <span class="acb-quote__ref">— 1 Pedro 3:15</span>
        </blockquote>
    </div>
</div>
@endsection
