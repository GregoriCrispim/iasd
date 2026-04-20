@extends('layouts.app')

@section('title', 'IASD Central de Brasília - A Igreja')

@push('styles')
<style>
    
    .igreja-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
    }

    /* Cola a 1ª seção na imagem do header e a 1ª na 2ª seção */
    .igreja-container > .igreja-intro {
        margin-bottom: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .igreja-container > .pilares-section {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .reveal-card {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .reveal-card.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .igreja-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .igreja-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .igreja-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .pilares-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .pilares-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .pilares-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .pilares-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 35px;
        margin-bottom: 30px;
    }

    .pilar-card {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 30px 25px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .pilar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
    }

    .pilar-card .emoji {
        font-size: 3em;
        margin-bottom: 15px;
        display: block;
    }

    .pilar-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #fff;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .pilar-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #f8f9fa;
        line-height: 1.6;
        text-align: center;
    }


    .estrutura-section {
        margin: 60px 0;
    }

    .estrutura-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .organograma-wrapper {
        padding: 0;
        margin-top: 10px;
        background: transparent;
        border-left: 0;
        border-radius: 0;
    }

    .organograma-intro {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        margin: 0 0 26px 0;
    }

    .piramide-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        margin: 18px 0 0 0;
        padding: 0;
    }

    .piramide-nivel {
        position: relative;
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 8px 14px;
        align-items: start;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        padding: 18px 18px 16px 18px;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e7eaf1;
        border-top: 4px solid var(--accent, #003366);
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .piramide-nivel:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.12);
    }

    .piramide-nivel.nivel-1 {
        --accent: #0b3a6e;
        --accent-soft: rgba(11, 58, 110, 0.12);
        max-width: 460px;
    }

    .piramide-nivel.nivel-2 {
        --accent: #0f4c8a;
        --accent-soft: rgba(15, 76, 138, 0.12);
        max-width: 540px;
    }

    .piramide-nivel.nivel-3 {
        --accent: #155ea6;
        --accent-soft: rgba(21, 94, 166, 0.12);
        max-width: 620px;
    }

    .piramide-nivel.nivel-4 {
        --accent: #1a72bf;
        --accent-soft: rgba(26, 114, 191, 0.12);
        max-width: 700px;
    }

    .piramide-nivel.nivel-5 {
        --accent: #1f86d9;
        --accent-soft: rgba(31, 134, 217, 0.12);
        max-width: 760px;
    }

    .piramide-nivel.nivel-6 {
        --accent: #2797ee;
        --accent-soft: rgba(39, 151, 238, 0.12);
        max-width: 820px;
    }

    .piramide-nivel:not(.nivel-6)::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: -18px;
        width: 2px;
        height: 18px;
        transform: translateX(-50%);
        background: linear-gradient(to bottom, rgba(0, 51, 102, 0.35), rgba(0, 51, 102, 0));
        border-radius: 2px;
        pointer-events: none;
    }

    .piramide-nivel h3 {
        grid-column: 2;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.35em;
        margin: 0 0 6px 0;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #0f2e52;
    }

    .piramide-nivel p {
        grid-column: 2;
        margin: 0;
    }

    .piramide-nivel .exemplo {
        font-family: 'Roboto', sans-serif;
        font-size: 0.98em;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .piramide-nivel .descricao {
        font-family: 'Roboto', sans-serif;
        font-size: 0.92em;
        line-height: 1.55;
        color: #475569;
        font-weight: 400;
    }

    .piramide-nivel .nivel-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.75em;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        background: var(--accent-soft, rgba(0, 51, 102, 0.12));
        color: var(--accent, #003366);
        letter-spacing: 0.3px;
    }

    .piramide-nivel .icone-wrap {
        grid-column: 1;
        grid-row: 1 / span 3;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: var(--accent-soft, rgba(0, 51, 102, 0.12));
        color: var(--accent, #003366);
    }

    .piramide-nivel .icone-wrap i {
        font-size: 1.35em;
        line-height: 1;
    }

    .piramide-nivel .nivel-body {
        grid-column: 2;
    }

    .seta-baixo {
        display: none;
    }

    .timeline-year {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3.5em;
        color: #003366;
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
    }

    .timeline-content {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #666;
        font-weight: 500;
    }

    .campanhas-info {
        background: transparent;
        padding: 0;
        border-radius: 0;
        margin: 0;
        border-left: 0;
    }

    .boletim-section {
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .boletim-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .boletim-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #f8f9fa;
        margin-bottom: 25px;
    }

    .btn-boletim {
        display: inline-block;
        background-color: #fff;
        color: #003366;
        padding: 12px 35px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-boletim:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
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

    .historia-subtitle {
        text-align: center;
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 30px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .historia-timeline {
        position: relative;
        max-width: 980px;
        margin: 10px auto 0 auto;
        padding: 8px 0;
    }

    .historia-timeline::before {
        content: "";
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 50%;
        width: 2px;
        transform: translateX(-50%);
        background: linear-gradient(to bottom, rgba(0, 51, 102, 0.05), rgba(0, 51, 102, 0.35), rgba(0, 51, 102, 0.05));
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 120px 1fr;
        gap: 0 18px;
        align-items: start;
        padding: 12px 0;
    }

    .timeline-marker {
        grid-column: 2;
        grid-row: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        justify-self: center;
        align-self: start;
        position: relative;
        z-index: 1;
        padding-top: 4px;
    }

    .timeline-dot {
        width: 58px;
        height: 58px;
        border-radius: 999px;
        background: #ffffff;
        border: 3px solid #003366;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.10);
        display: grid;
        place-items: center;
        color: #003366;
    }

    .timeline-dot i {
        font-size: 1.4em;
        line-height: 1;
    }

    .historia-timeline .timeline-year {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #003366;
        font-weight: 500;
        margin-top: 10px;
        line-height: 1;
        letter-spacing: 0.5px;
    }

    .timeline-card {
        position: relative;
        grid-row: 1;
        background: #fff;
        border: 1px solid #e7eaf1;
        border-radius: 16px;
        padding: 22px 22px 18px 22px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        max-width: 520px;
    }

    .timeline-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.12);
    }

    .timeline-card::after {
        content: "";
        position: absolute;
        top: 28px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border: 1px solid #e7eaf1;
        transform: rotate(45deg);
    }

    .timeline-item:nth-child(odd) .timeline-card {
        grid-column: 1;
        justify-self: end;
        border-top: 4px solid #0b3a6e;
    }

    .timeline-item:nth-child(odd) .timeline-card::after {
        right: -8px;
        border-left: 0;
        border-bottom: 0;
    }

    .timeline-item:nth-child(even) .timeline-card {
        grid-column: 3;
        justify-self: start;
        border-top: 4px solid #1a72bf;
    }

    .timeline-item:nth-child(even) .timeline-card::after {
        left: -8px;
        border-right: 0;
        border-top: 0;
    }

    .timeline-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.55em;
        color: #0f2e52;
        margin: 0 0 10px 0;
        font-weight: 500;
        letter-spacing: 0.4px;
    }

    .timeline-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #334155;
        line-height: 1.75;
        margin: 0;
        text-align: justify;
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
        max-width: 310px;
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


    .historia-expansivel {
        display: none;
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin-top: 30px;
        text-align: left;
    }

    .historia-expansivel.show {
        display: block;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .historia-expansivel h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .historia-expansivel h4:first-child {
        margin-top: 0;
    }

    .historia-expansivel p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        margin-bottom: 15px;
    }

    .historia-expansivel img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .historia-expansivel .verso {
        font-style: italic;
        color: #666;
        background: #fff;
        padding: 20px;
        border-left: 4px solid #003366;
        margin: 20px 0;
        border-radius: 5px;
    }


    .crencas-section {
        margin: 60px 0;
    }

    .crencas-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .crencas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }

    .crenca-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        text-align: left;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .crenca-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .crenca-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        color: #003366;
        transition: transform 0.3s;
    }

    .crenca-card:hover .crenca-icon {
        transform: scale(1.1);
    }

    .crencas-grid .crenca-card:nth-child(4n+1) .crenca-icon {
        background: #fef3c7;
    }

    .crencas-grid .crenca-card:nth-child(4n+2) .crenca-icon {
        background: #e0e7ff;
    }

    .crencas-grid .crenca-card:nth-child(4n+3) .crenca-icon {
        background: #fce7f3;
    }

    .crencas-grid .crenca-card:nth-child(4n) .crenca-icon {
        background: #cffafe;
    }

    .crenca-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .crenca-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }

    .crencas-cta {
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        padding: 50px 40px;
        border-radius: 15px;
        text-align: center;
        color: #fff;
    }

    .btn-crencas-destaque {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #ff6b35;
        color: #fff;
        padding: 18px 50px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.3em;
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        font-family: 'Roboto', sans-serif;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn-crencas-destaque:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        background-color: #e55a2b;
    }

    @media (max-width: 768px) {
        .igreja-container {
            padding: 20px 15px;
        }

        .igreja-intro {
            padding: 30px 20px;
        }

        .igreja-intro h1 {
            font-size: 2.2em;
        }

        .pilares-section {
            padding: 40px 20px;
        }

        .pilares-section h2 {
            font-size: 2em;
        }

        .estatisticas-grid,
        .pilares-grid,
        .crencas-grid {
            grid-template-columns: 1fr;
        }

        .timeline-year {
            font-size: 2.5em;
        }

        .piramide-nivel {
            width: 100% !important;
            padding: 14px 14px 12px 14px;
            grid-template-columns: 44px 1fr;
        }

        .piramide-nivel h3 {
            font-size: 1.2em;
        }

        .piramide-nivel .icone-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
        }

        .piramide-nivel .exemplo {
            font-size: 0.92em;
        }

        .piramide-nivel .descricao {
            font-size: 0.88em;
        }

        .contato-section > div[style*="display: flex"] {
            flex-direction: column;
            align-items: center;
            gap: 35px;
        }

        .historia-timeline {
            padding: 0 0 0 6px;
        }

        .historia-timeline::before {
            left: 28px;
            transform: none;
        }

        .timeline-item {
            grid-template-columns: 56px 1fr;
            gap: 0 14px;
            padding: 14px 0;
        }

        .timeline-marker {
            grid-column: 1;
        }

        .timeline-dot {
            width: 50px;
            height: 50px;
        }

        .historia-timeline .timeline-year {
            font-size: 1.85em;
        }

        .timeline-card {
            grid-column: 2 !important;
            justify-self: stretch !important;
            max-width: 100%;
        }

        .timeline-card::after {
            left: -8px;
            right: auto;
            border-right: 0;
            border-top: 0;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/igreja/fachada.webp') }}" alt="IASD Central de Brasília - A Igreja" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="igreja-container">
    
    <!-- Seção Introdutória -->
    <div class="igreja-intro acb-fullbleed">
        <h1>Quem Somos</h1>
        <p>
            A Igreja Adventista do Sétimo Dia é uma igreja cristã protestante com atuação mundial que teve suas primeiras raízes entre as décadas de 1850 e 1860, concomitantemente nos Estados Unidos e na Europa. Seu início se deu a partir de um grupo composto por homens e mulheres de várias denominações religiosas, estudiosos da Bíblia, que em 1863 organizou e oficializou uma estrutura denominacional, passando a adotar o nome atual.
        </p>
    </div>

    <!-- Seção Pilares de Nossa Fé -->
    <div class="pilares-section acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-building"></i> Pilares de Nossa Fé</h2>

        <div class="pilares-grid">
            <div class="pilar-card">
                <i class="bi bi-book-half emoji"></i>
                <h3>A Bíblia</h3>
                <p>Nossa única regra de fé e prática</p>
            </div>

            <div class="pilar-card">
                <svg class="emoji" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:1em; height:1em; margin: 0 auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3>A Trindade</h3>
                <p>Um só Deus em três pessoas (Pai, Filho e Espírito Santo)</p>
            </div>

            <div class="pilar-card">
                <i class="bi bi-stars emoji"></i>
                <h3>Jesus Cristo</h3>
                <p>O Salvador da humanidade, que morreu por nós, ressuscitou e prometeu voltar a esta Terra</p>
            </div>
        </div>
    </div>

    <!-- Seção Estrutura Organizacional -->
    <div class="estrutura-section">
        <h2 class="acb-title-serif"><i class="bi bi-map"></i> Estrutura Organizacional</h2>

        <div class="organograma-wrapper">
            <p class="organograma-intro">
                No Brasil, a mensagem adventista chegou por meio de impressos que ingressaram nas colônias de imigrantes alemães e austríacos, nos estados de Santa Catarina, São Paulo e Espírito Santo. Na última estatística em 2021, eram 21,9 milhões de membros em 212 países sendo que o Brasil é o país com maior número de adventistas no mundo.
            </p>

            <!-- Pirâmide Organizacional HTML -->
            <div class="piramide-container">
                <!-- Nível 1: Conferência Geral -->
                <div class="piramide-nivel nivel-1">
                    <span class="nivel-badge">Nível 1</span>
                    <span class="icone-wrap"><i class="bi bi-globe"></i></span>
                    <div class="nivel-body">
                        <h3>Conferência Geral</h3>
                        <p class="exemplo">Sede mundial — Maryland, EUA</p>
                        <p class="descricao">Supervisão global da Igreja em escala mundial</p>
                    </div>
                </div>

                <!-- Nível 2: Divisões -->
                <div class="piramide-nivel nivel-2">
                    <span class="nivel-badge">Nível 2</span>
                    <span class="icone-wrap"><i class="bi bi-map"></i></span>
                    <div class="nivel-body">
                        <h3>Divisões</h3>
                        <p class="exemplo">Divisão Sul-Americana</p>
                        <p class="descricao">Grandes áreas geográficas compostas por Uniões</p>
                    </div>
                </div>

                <!-- Nível 3: Uniões -->
                <div class="piramide-nivel nivel-3">
                    <span class="nivel-badge">Nível 3</span>
                    <span class="icone-wrap"><i class="bi bi-building"></i></span>
                    <div class="nivel-body">
                        <h3>Uniões</h3>
                        <p class="exemplo">União Centro-Oeste Brasileira</p>
                        <p class="descricao">Grupos de Associações/Missões dentro de um território</p>
                    </div>
                </div>

                <!-- Nível 4: Associações / Missões -->
                <div class="piramide-nivel nivel-4">
                    <span class="nivel-badge">Nível 4</span>
                    <span class="icone-wrap"><i class="bi bi-bank"></i></span>
                    <div class="nivel-body">
                        <h3>Associações / Missões</h3>
                        <p class="exemplo">Associação Planalto Central</p>
                        <p class="descricao">Conjunto de igrejas locais em uma área específica</p>
                    </div>
                </div>

                <!-- Nível 5: Igrejas Locais -->
                <div class="piramide-nivel nivel-5">
                    <span class="nivel-badge">Nível 5</span>
                    <span class="icone-wrap"><i class="bi bi-house-door"></i></span>
                    <div class="nivel-body">
                        <h3>Igrejas Locais</h3>
                        <p class="exemplo">IASD Central de Brasília</p>
                        <p class="descricao">Congregações de base formadas por membros</p>
                    </div>
                </div>

                <!-- Nível 6: Membros -->
                <div class="piramide-nivel nivel-6">
                    <span class="nivel-badge">Nível 6</span>
                    <span class="icone-wrap"><i class="bi bi-people-fill"></i></span>
                    <div class="nivel-body">
                        <h3>Membros</h3>
                        <p class="exemplo">Fiéis batizados</p>
                        <p class="descricao">Base fundamental que forma e sustenta as igrejas locais</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Nossa História -->
    <div class="como-ajudar-section">
        <h2 class="acb-title-serif"><i class="bi bi-hourglass-split"></i> Nossa História: IASD Brasilia</h2>
        <p class="historia-subtitle">
            Uma Jornada de Fé e Comunidade
        </p>

        <div class="historia-timeline" role="list" aria-label="Linha do tempo da IASD Brasília">
            <div class="timeline-item" role="listitem">
                <div class="timeline-card">
                    <h3>Primeiros encontros na Candangolândia</h3>
                    <p>
                        A dedicação do casal Walter e Antônia Leão foi fundamental. Eles abriram as portas de sua casa na Candangolândia para encontros de adoração a Deus com poucas pessoas, plantando a primeira semente adventista na região. Com o tempo, o casal se mudou para o Núcleo Bandeirante, mas a chama da missão continuou acesa, e os encontros evangelísticos prosseguiram.
                    </p>
                </div>
                <div class="timeline-marker" aria-hidden="true">
                    <div class="timeline-dot"><i class="bi bi-house-heart"></i></div>
                    <div class="timeline-year">1957</div>
                </div>
            </div>

            <div class="timeline-item" role="listitem">
                <div class="timeline-card">
                    <h3>Mudança para o Gama</h3>
                    <p>
                        No ano da inauguração de Brasília, Walter e Antônia foram para o Gama. O endereço mudou novamente, mas a paixão por compartilhar a fé permaneceu inabalável. Outras pessoas, inspiradas pelo mesmo ideal, uniram-se a eles para fazer a obra avançar na nova capital.
                    </p>
                </div>
                <div class="timeline-marker" aria-hidden="true">
                    <div class="timeline-dot"><i class="bi bi-geo-alt"></i></div>
                    <div class="timeline-year">1960</div>
                </div>
            </div>

            <div class="timeline-item" role="listitem">
                <div class="timeline-card">
                    <h3>Terreno e Capela Azul</h3>
                    <p>
                        Um personagem crucial nesta história foi Clayton Rossi, Procurador da República. Já membro da igreja em Belo Horizonte, ele se mudou para Brasília com a missão em seu coração. Movido por sua fé, Clayton empreendeu uma verdadeira maratona para garantir, junto ao Governo Federal, um grande terreno que se estendia da Avenida L-2 à Avenida L-3. Esse esforço foi recompensado, e a propriedade foi adquirida. O estabelecimento efetivo da Igreja Central de Brasília aconteceu a partir deste ano. Inicialmente, foi construído um salão simples no terreno adquirido, conhecido como Capela Azul.
                    </p>
                </div>
                <div class="timeline-marker" aria-hidden="true">
                    <div class="timeline-dot"><i class="bi bi-building"></i></div>
                    <div class="timeline-year">1967</div>
                </div>
            </div>

            <div class="timeline-item" role="listitem">
                <div class="timeline-card">
                    <h3>Inauguração do templo</h3>
                    <p>
                        Finalmente, o momento tão esperado chegou! O templo da Igreja Central de Brasília foi inaugurado em 8 de dezembro de 1968. Cerca de 60 adventistas assinaram a ata de inauguração. Desde seus primeiros dias até hoje, a Igreja Central de Brasília cresceu e se consolidou como uma grande e influente comunidade adventista, servindo de inspiração e apoio para outras igrejas na capital federal.
                    </p>
                </div>
                <div class="timeline-marker" aria-hidden="true">
                    <div class="timeline-dot"><i class="bi bi-stars"></i></div>
                    <div class="timeline-year">1968</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção O Temporal que Uniu uma Comunidade -->
    <div class="boletim-section acb-fullbleed">
        <h3 class="acb-title-serif"><i class="bi bi-cloud-rain"></i> O Temporal que Uniu uma Comunidade</h3>
        <p>
            Na véspera da inauguração (7/12/1968), um temporal inundou o templo. Membros trabalharam a noite toda para limpar a igreja, garantindo que, ao amanhecer, tudo estivesse impecável para receber visitantes de todo o Brasil.
        </p>
        <div style="text-align: center; margin-top: 30px;">
            <a href="javascript:void(0)" id="btn-historia" style="display: inline-block; background-color: #ff6b35; color: #fff; padding: 12px 35px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 1em; transition: transform 0.3s, box-shadow 0.3s; font-family: 'Roboto', sans-serif; cursor: pointer;" onmouseover="this.style.backgroundColor='#e55a2b'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.3)'" onmouseout="this.style.backgroundColor='#ff6b35'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">Veja mais sobre nossa história</a>
        </div>
        <div id="historia-expansivel" class="historia-expansivel">
            <p>
                Era o dia 08 de dezembro de 1968. A Cidade de Brasília, a nova Capital Federal do Brasil contava com apenas oito anos de inaugurada. Na linguagem maternal, estava apenas engatinhando. Estávamos vivendo uma nova época, cheia de expectativas e vislumbres de um futuro promissor. Havia chegado, finalmente, o tão almejado dia da inauguração do grande Templo da Igreja Adventista do Sétimo Dia, onde Cultos Divinos seriam celebrados para honra e glória do Senhor Deus Triúno, o TodoPoderoso.
            </p>

            <p>
                Assim a ordem Divina está escrita: "E Me farão um Santuário para que Eu habite no meio deles" exarada no Livro de Êxodo 25:8, estava sendo cumprida.
            </p>

            <p>
                Pelo exercício da fé e pelo esforço determinado de muitos, a magnífica realidade ali estava presente, numa demonstração de que aquela máxima citada pelo Apóstolo Paulo aos Filipenses capítulo 4, verso 13, inspirada pelo Espírito da Profecia, de que, "Tudo posso nAquele que me fortalece", estava sendo transformada em uma verdade deslumbrante, real, concreta esplendorosa, sublime, bem presente, cheia de luz, "e a Glória do Senhor Deus encheu o Templo" (II Crônicas 5:14).
            </p>

            <img src="{{ asset('img/igreja/inauguracao.webp') }}" alt="Inauguração da Igreja Central de Brasília" loading="lazy" decoding="async">

            <p>
                Naquele dia esta bela Igreja, esta Casa de Deus, nova, exuberante e confortável, estava pronta para ser dedicada ao Senhor Deus; e assim foi, para honra e glória do nosso Pai Eterno, a quem tudo devemos.
            </p>

            <p>
                O terreno onde está construída a Igreja tem a área total de 25.000 m², medindo 100 metros de frente por 250 metros de fundos, foi uma doação do Governo do Brasil à União Sul Brasileira, com a intermediação incansável do saudoso irmão Dr. João Batista Clayton Rossi, Procurador da República.
            </p>

            <img src="{{ asset('img/igreja/construcao.webp') }}" alt="Construção do Templo" loading="lazy" decoding="async">

            <p>
                De acordo com as informações colhidas com o Pr. Wilson Sarli, então Presidente da Missão Brasil Central da IASD, um dos vespertinos da Capital Federal anunciou: "Igreja Adventista inaugura Templo e reúne fiéis do DF". E acrescenta: "Foi inaugurada, às 11 horas de ontem, na Avenida L2 Sul, o novo Templo da Igreja Adventista, com o descerramento da fita pelo presidente mundial daquela Igreja, Pastor Roberto H. Pierson, e o Senador Carvalho Pinto, especialmente convidado para a cerimônia".
            </p>

            <img src="{{ asset('img/igreja/coral_taguatinga.webp') }}" alt="Coral de Taguatinga na Inauguração" loading="lazy" decoding="async">

            <p>
                Conforme informações colhidas, cinco ônibus chegaram de várias partes do Estado de Goiás, trazendo irmãos para a cerimônia de inauguração, além de mais outros dez ônibus e inúmeros carros particulares com pessoas de outros Estados.
            </p>

            <p>
                Após o descerramento da fita, a grande porta de vidro foi aberta e o Coral da Igreja Adventista de Taguatinga entoou o hino de nº 18, do então Hinário Cantai ao Senhor: SANTO, SANTO, SANTO.
            </p>

            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e0e0e0; text-align: center;">
                <img src="{{ asset('img/igreja/dr-josias-gonsioroski.webp') }}"
                     alt="Dr. Josias Gonsioroski"
                     loading="lazy" decoding="async"
                     style="max-width: 300px; height: auto; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin: 0 auto 20px auto; display: block;">

                <p style="font-size: 0.9rem; color: #666; font-style: italic; text-align: center; margin: 0;">
                    * Conteúdo retirado do livro de autoria do Dr. Josias Gonsioroski, advogado de carreira, mestre em Teologia e membro na Igreja Adventista Do Sétimo Dia Central de Brasília. Com sólida trajetória jurídica, consolidou sua atuação profissional na capital federal, onde também desenvolve um expressivo trabalho voluntário e religioso.
                </p>
            </div>

            <p style="text-align: center; margin-top: 30px; font-size: 0.95rem; color: #333;">
                Para acesso ao conteúdo completo, pode baixar pelo link
                <a href="https://drive.google.com/file/d/1r3p6dMnht_2jf2n_np5ytpKhqnVmYK87/view?usp=sharing"
                   target="_blank"
                   style="color: #003366; text-decoration: underline; font-weight: 600;">
                    clicando aqui
                </a>.
            </p>
        </div>
    </div>

    <!-- Seção Em Que Cremos -->
    <div class="crencas-section">
        <h2 class="acb-title-serif"><i class="bi bi-book-half"></i> Em Que Cremos</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 40px; max-width: 800px; margin-left: auto; margin-right: auto;">
            Os adventistas do sétimo dia baseiam suas crenças integralmente nas Sagradas Escrituras. Aceitamos a Bíblia como nossa única regra de fé e prática.
        </p>

        <div class="crencas-grid">
            <div class="crenca-card">
                <i class="bi bi-feather crenca-icon"></i>
                <h4>Deus</h4>
                <p>Cremos em Deus como Pai, Filho e Espírito Santo, um Deus em três pessoas</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-book-half crenca-icon"></i>
                <h4>A Bíblia</h4>
                <p>As Escrituras Sagradas são a única regra de fé e prática cristã</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-plus-circle crenca-icon"></i>
                <h4>Salvação</h4>
                <p>Jesus Cristo morreu por nossos pecados e oferece salvação pela graça</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-arrow-repeat crenca-icon"></i>
                <h4>Retorno de Cristo</h4>
                <p>Jesus voltará pessoal e visivelmente a esta Terra para buscar seu povo</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-moon crenca-icon"></i>
                <h4>Morte e Ressurreição</h4>
                <p>A morte é um sono inconsciente até a ressurreição no dia de Cristo</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-bank crenca-icon"></i>
                <h4>Santuário</h4>
                <p>Há um santuário no céu onde Cristo ministra em nosso favor</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-file-text crenca-icon"></i>
                <h4>Lei de Deus</h4>
                <p>Os Dez Mandamentos refletem o caráter de Deus e são válidos hoje</p>
            </div>

            <div class="crenca-card">
                <i class="bi bi-droplet-fill crenca-icon"></i>
                <h4>Batismo</h4>
                <p>O batismo por imersão é símbolo de morte para o pecado e nova vida</p>
            </div>
        </div>

        <div class="crencas-cta acb-fullbleed">
            <h3 class="acb-title-serif" style="font-size: 1.8em; color: #ffffff; margin-bottom: 20px; font-weight: 700;">
                Conheça Nossas 28 Crenças Fundamentais
            </h3>
            <p style="font-family: 'Roboto', sans-serif; font-size: 1rem; color: #f8f9fa; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto;">
                Acesse gratuitamente a publicação "Nisto Cremos" para conhecer em detalhes todas as crenças que a Igreja Adventista sustenta a respeito dos ensinos bíblicos.
            </p>
            <a href="https://www.institutodemissao.org.br/wp-content/uploads/2021/07/Nisto-Cremos.pdf" target="_blank" class="btn-crencas-destaque">
                <i class="bi bi-book-half" style="font-size: 1.5em;"></i>
                Ler "Nisto Cremos"
            </a>
        </div>
    </div>

    <!-- Seção Liderança -->
    <div class="contato-section">
        <h2 style="font-family: 'Bebas neue', sans-serif; font-size: 2.5em; color: #003366; text-align: center; margin-bottom: 40px; font-weight: 500;">Liderança</h2>

        <div style="display: flex; justify-content: center; gap: 35px; flex-wrap: wrap; margin-top: 30px;">
            <!-- Pastor Lucas Alves -->
            <div style="text-align: center; max-width: 310px;">
                <img src="{{ asset('img/igreja/pr-lucas.webp') }}"
                     alt="Pastor Lucas Alves"
                     loading="lazy" decoding="async"
                     style="width: 310px; height: 310px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 15px;">
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.5em; color: #003366; margin-bottom: 5px;">Pastor Lucas Alves</h3>
                <p style="font-family: 'Roboto', sans-serif; font-size: 1em; color: #666; font-weight: 600;">Pastor Sênior</p>
            </div>

            <!-- Pastor Hugo Rodrigues -->
            <div style="text-align: center; max-width: 310px;">
                <img src="{{ asset('img/igreja/pr-hugo.webp') }}"
                     alt="Pastor Hugo Rodrigues"
                     loading="lazy" decoding="async"
                     style="width: 310px; height: 310px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 15px;">
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.5em; color: #003366; margin-bottom: 5px;">Pastor Hugo Rodrigues</h3>
                <p style="font-family: 'Roboto', sans-serif; font-size: 1em; color: #666; font-weight: 600;">Área Jovem</p>
            </div>

            <!-- Pastor Adriano Rezende -->
            <div style="text-align: center; max-width: 310px;">
                <img src="{{ asset('img/igreja/pr-adriano.webp') }}"
                     alt="Pastor Adriano Rezende"
                     loading="lazy" decoding="async"
                     style="width: 310px; height: 310px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 15px;">
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.5em; color: #003366; margin-bottom: 5px;">Pastor Adriano Rezende</h3>
                <p style="font-family: 'Roboto', sans-serif; font-size: 1em; color: #666; font-weight: 600;">Área Missionária</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animação suave de scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });


    // Toggle da história expansível
    const btnHistoria = document.getElementById('btn-historia');
    const historiaExpansivel = document.getElementById('historia-expansivel');

    if (btnHistoria && historiaExpansivel) {
        btnHistoria.addEventListener('click', function(e) {
            e.preventDefault();
            historiaExpansivel.classList.toggle('show');

            // Change button text based on state
            if (historiaExpansivel.classList.contains('show')) {
                btnHistoria.textContent = 'Recolher história';
            } else {
                btnHistoria.textContent = 'Veja mais sobre nossa história';
            }
        });
    }

    // Animação de fade-in para cards
    const cards = document.querySelectorAll('.pilar-card, .timeline-card, .crenca-card, .piramide-nivel');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, index * 100);
            }
        });
    }, { threshold: 0.2 });

    cards.forEach(card => {
        card.classList.add('reveal-card');
        observer.observe(card);
    });
});
</script>
@endpush

