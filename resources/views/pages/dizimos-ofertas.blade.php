@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Dízimos e Ofertas')

@section('meta-description', 'Contribua com a obra de Deus através de dízimos e ofertas na IASD Central de Brasília. Saiba como fazer sua contribuição com segurança e facilidade.')
@section('og-title', 'Dízimos e Ofertas - IASD Central de Brasília')
@section('og-description', 'Sua contribuição faz a diferença! Saiba como apoiar o trabalho da igreja.')
@section('page-name', 'Dízimos e Ofertas')

@push('styles')
<style>

    .dizimos-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
    }

    .dizimos-header-wrap {
        width: 100%;
        overflow: hidden;
        aspect-ratio: 1920 / 300;
    }

    .dizimos-page-header-img {
        width: 100%;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .dizimos-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 40px 40px 30px 40px;
        border-radius: 15px;
        margin-bottom: 0;
        text-align: center;
    }

    /* Cola a 1ª seção na imagem do header e une a 1ª com a 2ª */
    .dizimos-container > .dizimos-intro {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .dizimos-container > .dizimo-section {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .dizimos-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .dizimos-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .dizimo-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .dizimo-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .dizimo-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        margin-bottom: 30px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .dizimo-section blockquote {
        background: rgba(255,255,255,0.1);
        border-left: 4px solid #fff;
        padding: 20px 30px;
        margin: 30px auto;
        max-width: 800px;
        text-align: center;
        font-style: italic;
        font-size: 1.2rem;
    }

    .dizimo-section .referencia {
        display: block;
        margin-top: 15px;
        font-weight: 600;
    }

    .ofertas-section {
        background: #f8f9fa;
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        border-top: 4px solid rgba(0, 51, 102, 0.22);
    }

    .ofertas-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        margin-bottom: 30px;
        font-weight: 500;
        text-align: center;
    }

    .ofertas-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .ofertas-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 20px;
    }

    .ofertas-section blockquote {
        background: #fff;
        border-left: 4px solid #003366;
        padding: 20px 30px;
        margin: 30px 0;
        font-style: italic;
        font-size: 1.1rem;
    }

    .beneficios-section {
        margin: 60px 0;
    }

    .beneficios-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .beneficios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .beneficio-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        text-align: left;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .beneficio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .beneficio-card .emoji {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        transition: transform 0.3s;
    }

    .beneficio-card:hover .emoji {
        transform: scale(1.1);
    }

    .beneficios-grid .beneficio-card:nth-child(4n+1) .emoji {
        background: #fef3c7;
    }

    .beneficios-grid .beneficio-card:nth-child(4n+2) .emoji {
        background: #e0e7ff;
    }

    .beneficios-grid .beneficio-card:nth-child(4n+3) .emoji {
        background: #fce7f3;
    }

    .beneficios-grid .beneficio-card:nth-child(4n) .emoji {
        background: #cffafe;
    }

    .beneficio-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .beneficio-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }

    .como-contribuir-section {
        margin: 60px 0;
    }

    .como-contribuir-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .formas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .forma-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: left;
    }

    .forma-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .forma-card .icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        transition: transform 0.3s;
    }

    .forma-card:hover .icon {
        transform: scale(1.1);
    }

    .formas-grid .forma-card:nth-child(3n+1) .icon {
        background: #fef3c7;
    }

    .formas-grid .forma-card:nth-child(3n+2) .icon {
        background: #e0e7ff;
    }

    .formas-grid .forma-card:nth-child(3n) .icon {
        background: #cffafe;
    }

    .forma-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .forma-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
    }

    .forma-card a {
        color: #003366;
        text-decoration: none;
        font-weight: 600;
    }

    .forma-card a:hover {
        text-decoration: underline;
    }

    .btn-contribuir {
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

    .btn-contribuir:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .transparencia-section {
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .transparencia-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fff;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .transparencia-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #f8f9fa;
        max-width: 900px;
        margin: 0 auto;
    }

    .reflexao-section {
        background: #f8f9fa;
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    .reflexao-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .reflexao-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 25px;
    }

    .reflexao-section blockquote {
        background: #fff;
        border-left: 5px solid #003366;
        padding: 25px 35px;
        margin: 30px auto;
        max-width: 800px;
        font-style: italic;
        font-size: 1.15rem;
        color: #003366;
    }

    .reflexao-section .referencia {
        display: block;
        margin-top: 15px;
        font-weight: 600;
    }

    .contato-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 45px 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
    }

    .contato-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fff;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .contato-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #f8f9fa;
        margin-bottom: 15px;
    }

    .testemunhos-section {
        margin: 60px 0 30px 0;
    }

    .testemunhos-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    /* Estilos do carrossel Provai e Vede */
    .pa-widget.pa-w-provaievede {
        padding: 2rem 0 1rem 0;
        position: relative;
        background-color: #f8f9fa;
        margin-bottom: 0;
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }

    .pa-widget.pa-w-provaievede .row {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 3rem;
        overflow: hidden;
    }

    .pa-widget.pa-w-provaievede .col {
        overflow: hidden;
        width: 100%;
    }

    .pa-glide-provaievede {
        position: relative;
        width: 100%;
    }

    .pa-glide-provaievede .glide__track {
        overflow: hidden;
        width: 100%;
    }

    .pa-glide-provaievede .glide__slides {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pa-glide-provaievede .glide__slide {
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
        display: block;
        width: 100%;
        height: 100%;
        text-decoration: none;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .carousel-image-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .carousel-image-link img {
        display: block;
        width: 100%;
        height: 191px;
        object-fit: cover;
        border-radius: 0.5rem;
        transition: transform 0.3s ease;
    }

    .pa-glide-provaievede .glide__slide:hover .carousel-image-link img {
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
        background-color: #003366;
        color: #fff;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .pa-slider-btn:hover {
        background-color: #004488;
        transform: scale(1.1);
    }

    .pa-slider-bullet-btn {
        background-color: #ccc;
        border: none;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0 4px;
        padding: 0;
    }

    .pa-slider-bullet-btn:hover {
        background-color: #999;
        transform: scale(1.2);
    }

    .pa-slider-bullet-btn.active {
        background-color: #003366;
        transform: scale(1.3);
    }

    .pa-slider-header {
        margin-bottom: 2rem;
    }

    .pa-logo-link {
        text-decoration: none;
        transition: opacity 0.3s ease;
        margin-left: 2rem;
    }

    .pa-slider-header .btn {
        margin-right: 2rem;
    }

    .pa-logo-link:hover {
        opacity: 0.8;
    }

    .btn {
        display: inline-block;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        font-family: 'Roboto', sans-serif;
    }

    .btn-primary {
        color: #fff;
        background-color: #003366;
        border-color: #003366;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #004488;
        border-color: #004488;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,51,102,0.3);
    }

    .pa-slider-btn i {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
    }

    .pa-w-provaievede .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .pa-w-provaievede .col {
        flex-basis: 0;
        flex-grow: 1;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .glide__arrow {
        z-index: 10;
    }

    .pa-slider-controle {
        position: relative;
        z-index: 10;
    }

    .d-flex {
        display: flex !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .w-100 {
        width: 100% !important;
    }

    .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .position-relative {
        position: relative !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .mb-5 {
        margin-bottom: 3rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .mx-1 {
        margin-left: 0.25rem !important;
        margin-right: 0.25rem !important;
    }

    .mx-2 {
        margin-left: 0.5rem !important;
        margin-right: 0.5rem !important;
    }

    @media (max-width: 768px) {
        .dizimos-container {
            padding: 0 15px 20px;
        }

        .dizimos-intro {
            padding: 30px 20px;
        }

        .dizimos-intro h1 {
            font-size: 2.2em;
        }

        .dizimo-section,
        .transparencia-section,
        .contato-section {
            padding: 40px 20px;
        }

        .dizimo-section h2 {
            font-size: 2em;
        }

        .beneficios-grid,
        .formas-grid {
            grid-template-columns: 1fr;
        }

        .pa-widget.pa-w-provaievede .row {
            padding: 0 0.5rem;
            overflow: visible;
        }

        .pa-glide-provaievede .glide__slide {
            height: auto;
        }

        .pa-glide-provaievede .carousel-image-link {
            position: relative;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        .pa-glide-provaievede .carousel-image-link img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pa-slider-controle {
            display: grid !important;
            grid-template-columns: 36px 1fr 36px;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }

        #provaievede-bullets {
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding: 0 6px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        #provaievede-bullets::-webkit-scrollbar {
            display: none;
        }

        .pa-slider-btn {
            width: 36px;
            height: 36px;
        }

        .pa-slider-bullet-btn {
            width: 8px;
            height: 8px;
            margin: 0 2px;
        }

        .pa-slider-bullet-btn.active {
            transform: scale(1.25);
        }
    }
</style>
@endpush

@section('content')
<div class="dizimos-header-wrap">
    <img class="dizimos-page-header-img" src="{{ asset('img/dizimos_ofertas/dizimos_ofertas_header.webp') }}" alt="Dízimos e Ofertas" fetchpriority="high" decoding="async">
</div>

<div class="dizimos-container">

    <!-- Seção Introdutória -->
    <div class="dizimos-intro acb-fullbleed">
        <h1>Dízimos e Ofertas: Adoração, Fidelidade e Parceria com Deus</h1>
        <p>
            Compreender e praticar a devolução dos dízimos e a entrega das ofertas é uma parte fundamental da jornada de fé e adoração. Mais do que uma obrigação, vemos esses atos como uma resposta de amor, gratidão e reconhecimento de que tudo o que temos pertence a Deus.
        </p>
        <p>
            É uma forma de nos associarmos a Ele em Sua missão de espalhar o Evangelho e cuidar das necessidades do mundo, sendo fiéis mordomos dos recursos que Ele nos confia.
        </p>
    </div>

    <!-- Seção Dízimo -->
    <div class="dizimo-section acb-fullbleed">
        <h2 class="acb-title-serif">O que é o Dízimo?</h2>
        <p>
            A palavra "dízimo" significa literalmente "a décima parte". Biblicamente, refere-se à devolução de 10% de toda a nossa renda a Deus. Não é um pagamento, mas um ato de reconhecimento de que Ele é o Dono e Provedor de tudo.
        </p>
        <p>
            O princípio do dízimo é anterior à lei mosaica, praticado por patriarcas como Abraão (Gênesis 14:20) e Jacó (Gênesis 28:22). Foi formalizado na lei de Israel (Levítico 27:30, Números 18:21) e reafirmado por Jesus (Mateus 23:23).
        </p>
        <p>
            <strong>Propósito:</strong> O dízimo é primariamente destinado ao sustento do ministério evangélico – pastores, obreiros bíblicos e missionários – permitindo a pregação do evangelho em todo o mundo.
        </p>
        <blockquote class="acb-quote acb-quote--glass">
            <p>
                "Trazei todos os dízimos à casa do tesouro, para que haja mantimento na minha casa; e provai-me nisto, diz o Senhor dos Exércitos, se eu não vos abrir as janelas do céu e não derramar sobre vós bênção sem medida."
            </p>
            <span class="referencia">— Malaquias 3:10</span>
        </blockquote>
    </div>

    <!-- Seção Ofertas -->
    <div class="ofertas-section acb-fullbleed">
        <h2 class="acb-title-serif">O que são as Ofertas?</h2>
        <p>
            Diferente do dízimo (que é 10%), as ofertas são contribuições voluntárias, dadas de coração, além do dízimo. Elas representam nossa gratidão pelas bênçãos de Deus e nosso desejo de apoiar causas específicas da igreja e necessidades humanitárias.
        </p>

        <h3 class="acb-title-serif">Destino das Ofertas</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0; color: #003366; font-weight: bold;">✓</span>
                Necessidades da igreja local (manutenção, materiais, ministérios)
            </li>
            <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0; color: #003366; font-weight: bold;">✓</span>
                Projetos missionários específicos (nacionais e internacionais)
            </li>
            <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0; color: #003366; font-weight: bold;">✓</span>
                Ação social e ajuda humanitária (<a href="https://adra.org.br/" target="_blank" style="color: #003366; font-weight: 600;">ADRA</a>, <a href="https://adventistascentralbrasilia.org/asa" target="_blank" style="color: #003366; font-weight: 600;">ASA</a>)
            </li>
            <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0; color: #003366; font-weight: bold;">✓</span>
                Construção e reforma de templos e escolas
            </li>
            <li style="padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0; color: #003366; font-weight: bold;">✓</span>
                Outros projetos especiais definidos pela comunidade
            </li>
        </ul>

        <blockquote class="acb-quote">
            <p>
                "Cada um contribua segundo tiver proposto no coração, não com tristeza ou por necessidade; porque Deus ama a quem dá com alegria."
            </p>
            <span class="referencia" style="display: block; margin-top: 10px; font-weight: 600; color: #003366;">— 2 Coríntios 9:7</span>
        </blockquote>
    </div>

    <!-- Seção Benefícios -->
    <div class="beneficios-section">
        <h2 class="acb-title-serif">Por que Devolver o Dízimo e Ofertar?</h2>

        <div class="beneficios-grid">
            <div class="beneficio-card">
                <i class="bi bi-heart emoji"></i>
                <h4>Ato de Adoração</h4>
                <p>Dar é uma forma tangível de adorar a Deus, reconhecendo Sua grandeza e bondade em nossa vida.</p>
            </div>

            <div class="beneficio-card">
                <i class="bi bi-lightning emoji"></i>
                <h4>Expressão de Fé</h4>
                <p>Demonstramos nossa confiança de que Deus continuará a prover todas as nossas necessidades.</p>
            </div>

            <div class="beneficio-card">
                <i class="bi bi-emoji-smile emoji"></i>
                <h4>Desenvolvimento do Caráter</h4>
                <p>A generosidade combate o egoísmo e o materialismo, moldando nosso caráter à semelhança de Cristo.</p>
            </div>

            <div class="beneficio-card">
                <i class="bi bi-globe2 emoji"></i>
                <h4>Parceria na Missão</h4>
                <p>Contribuímos diretamente para o avanço da obra de Deus na Terra, tornando-nos Seus colaboradores.</p>
            </div>

            <div class="beneficio-card">
                <i class="bi bi-heart-fill emoji"></i>
                <h4>Obediência por Amor</h4>
                <p>É uma resposta de amor aos mandamentos de Deus, motivada pela gratidão por Sua salvação.</p>
            </div>

            <div class="beneficio-card">
                <i class="bi bi-star-fill emoji"></i>
                <h4>Bênçãos Prometidas</h4>
                <p>Deus promete abençoar os fiéis. Essas bênçãos podem ser espirituais, materiais e a alegria de participar de Sua obra.</p>
            </div>
        </div>
    </div>

    <!-- Seção Como Contribuir -->
    <div class="como-contribuir-section">
        <h2 class="acb-title-serif">Como Contribuir na IASD Central?</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto;">
            Entendemos a importância de facilitar seu ato de adoração através da contribuição. Escolha a forma mais conveniente para você:
        </p>

        <div class="formas-grid">
            <div class="forma-card">
                <i class="bi bi-laptop icon"></i>
                <h4>Online (7me)</h4>
                <p>Através do sistema oficial da igreja. É seguro, prático e rápido.</p>
                <p style="margin-top: 10px;"><a href="https://play.google.com/store/apps/details?id=com.iatec.acms.me&hl=pt_BR&pli=1" target="_blank">Acesse o 7me →</a></p>
            </div>

            <div class="forma-card">
                <i class="bi bi-bank icon"></i>
                <h4>Transferência/PIX</h4>
                <p><strong>Banco:</strong> Banco do Brasil</p>
                <p><strong>Favorecido:</strong> União Centro Oeste Brasileira da IASD</p>
                <p><strong>Agência:</strong> 3380-4</p>
                <p><strong>Conta:</strong> 39999-X</p>
                <p><strong>CNPJ:</strong> 07.121.135/0004-5</p>
                <p><strong>Chave PIX:</strong> pix.centralbsb.aplac@adventistas.org</p>
                <p style="margin-top: 15px; font-size: 0.95rem; color: #003366; font-weight: 600; background: rgba(0, 51, 102, 0.08); padding: 10px; border-radius: 6px;">* Envie o comprovante para: tesouraria.centralbsb@gmail.com</p>
            </div>

            <div class="forma-card">
                <i class="bi bi-envelope-fill icon"></i>
                <h4>Envelope</h4>
                <p>Disponíveis na igreja. Preencha seus dados e deposite nos gazofilácios durante os cultos ou entregue na tesouraria.</p>
            </div>
        </div>
    </div>

    <!-- Seção Transparência -->
    <div class="transparencia-section">
        <h3>Transparência e Responsabilidade</h3>
        <p>
            Garantimos que todos os recursos são administrados com responsabilidade e transparência, seguindo as diretrizes financeiras da Igreja Adventista do Sétimo Dia, com auditorias regulares. Relatórios financeiros podem ser consultados junto à tesouraria.
        </p>
        <div style="background: rgba(255,255,255,0.15); border-radius: 10px; padding: 30px; margin-top: 30px;">
            <p style="font-size: 1.15rem; font-weight: 600; margin-bottom: 20px;">
                <i class="bi bi-journals"></i> Saiba Mais Sobre Dízimos e Ofertas
            </p>
            <p style="margin-bottom: 20px;">
                Se quiser conhecer mais sobre os dízimos e ofertas, acesse os documentos abaixo e/ou visite a página oficial de mordomia cristã da igreja adventista.
            </p>
            <div style="display: flex; justify-content: center; margin-top: 25px;">
                <a href="https://www.adventistas.org/pt/mordomiacrista/" target="_blank" style="display: inline-block; background: #fff; color: #003366; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: transform 0.3s, box-shadow 0.3s; font-family: 'Roboto', sans-serif;">
                    Mordomia Cristã →
                </a>
            </div>
        </div>
    </div>

    <!-- Seção Reflexão -->
    <div class="reflexao-section acb-fullbleed">
        <h3 class="acb-title-serif">Reflita</h3>
        <p>
            Devolver o dízimo e entregar ofertas são privilégios que nos conectam mais profundamente com Deus e Sua missão. Que possamos experimentar a alegria e as bênçãos de sermos fiéis mordomos dos recursos que Ele nos confia.
        </p>
        <p>
            Se tiver dúvidas ou precisar de mais informações, não hesite em procurar a tesouraria ou um dos líderes de nossa igreja.
        </p>
        <blockquote class="acb-quote">
            <p>
                "[...] mais bem-aventurado é dar que receber."
            </p>
            <span class="referencia">— Atos 20:35</span>
        </blockquote>
    </div>

    <!-- Seção Testemunhos -->
    <div class="testemunhos-section">
        <h2>Testemunhos (Provai e Vede)</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto;">
            Assista aos testemunhos mais recentes e veja como a fidelidade a Deus transforma vidas.
        </p>

        <!-- Carrossel de Vídeos Provai e Vede -->
        <div class="pa-widget pa-w-provaievede py-4 col-12 position-relative bg-light">
            <div class="pa-slider-header mb-4">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <a
                        href="https://www.youtube.com/@provaievedeoficial"
                        target="_blank"
                        rel="noopener"
                        class="pa-logo-link"
                    >
                        <h3 style="margin: 0; color: #003366; font-family: 'Bebas neue', sans-serif; font-size: 1.8em;">Provai e Vede</h3>
                    </a>
                    <a
                        href="https://www.youtube.com/@provaievedeoficial"
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
                    <div class="pa-glide-provaievede"
                         data-autoplay="2500"
                         data-format="100">
                        <div class="glide__track" data-glide-el="track">
                            <div class="glide__slides" id="provaievede-slides">
                                <!-- Slides serão preenchidos via JavaScript -->
                            </div>
                        </div>

                        <div class="pa-slider-controle d-flex align-items-center mt-4">
                            <div data-glide-el="controls">
                                <button type="button" class="pa-slider-btn" data-glide-dir="&lt;" aria-label="Anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </div>
                            <div class="mx-2 pa-slider-bullet" id="provaievede-bullets" data-glide-el="controls[nav]">
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

    <!-- Seção Contato -->
    <div class="contato-section">
        <h3>Precisa de Ajuda?</h3>
        <p>
            Entre em contato conosco para mais informações sobre como contribuir:
        </p>
        <p style="margin-top: 20px;">
            <strong><i class="bi bi-envelope"></i> Email:</strong> tesouraria@iasdcentraldebrasilia.com.br
        </p>
        <p style="margin-top: 15px;">
            <strong><i class="bi bi-geo-alt-fill"></i> Local:</strong> Tesouraria da IASD Central de Brasília
        </p>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.theme.min.css" media="print" onload="this.media='all'">
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiUrlProvaiEVede = (window.APP_URL || '') + '/api/videos-provaievede';

    fetch(apiUrlProvaiEVede)
        .then(response => response.json())
        .then(videos => {
            if (!videos || videos.length === 0 || videos.error) {
                console.error('Erro ao carregar vídeos do Provai e Vede:', videos.error || 'Nenhum vídeo encontrado');
                return;
            }

            const slidesContainer = document.getElementById('provaievede-slides');
            const bulletsContainer = document.getElementById('provaievede-bullets');

            if (!slidesContainer || !bulletsContainer) return;

            slidesContainer.innerHTML = '';
            bulletsContainer.innerHTML = '';

            videos.forEach((video, index) => {
                const slide = document.createElement('li');
                slide.className = 'glide__slide';

                const link = document.createElement('a');
                link.href = video.url || `https://www.youtube.com/watch?v=${video.id}`;
                link.target = '_blank';
                link.rel = 'noopener';
                link.className = 'carousel-image-link';
                link.title = video.title || 'Testemunho';

                const img = document.createElement('img');
                img.src = video.thumbnail || `https://img.youtube.com/vi/${video.id}/mqdefault.jpg`;
                img.alt = video.title || 'Testemunho';
                img.loading = 'lazy';

                link.appendChild(img);
                slide.appendChild(link);
                slidesContainer.appendChild(slide);

                const bullet = document.createElement('button');
                bullet.className = 'pa-slider-bullet-btn';
                bullet.setAttribute('data-glide-dir', `=${index}`);
                bullet.setAttribute('aria-label', `Ir para slide ${index + 1}`);
                bulletsContainer.appendChild(bullet);
            });

            const provaievedeCarousel = document.querySelector('.pa-glide-provaievede');
            if (provaievedeCarousel && videos.length > 0) {
                const autoplay = provaievedeCarousel.getAttribute('data-autoplay') || 2500;

                const glideProvaiEVede = new Glide('.pa-glide-provaievede', {
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
                    breakpoints: {
                        1024: {
                            perView: 3,
                            gap: 15
                        },
                        768: {
                            perView: 2,
                            gap: 10
                        },
                        480: {
                            perView: 1,
                            gap: 0,
                            rewind: true
                        }
                    }
                });

                function updateBulletsProvaiEVede() {
                    const bullets = document.querySelectorAll('#provaievede-bullets button');
                    const totalSlides = bullets.length;
                    if (totalSlides === 0) return;

                    let currentIndex = glideProvaiEVede.index;

                    if (currentIndex < 0) {
                        currentIndex = ((currentIndex % totalSlides) + totalSlides) % totalSlides;
                    } else if (currentIndex >= totalSlides) {
                        currentIndex = currentIndex % totalSlides;
                    }

                    bullets.forEach((bullet, idx) => {
                        if (idx === currentIndex) {
                            bullet.classList.add('active');
                        } else {
                            bullet.classList.remove('active');
                        }
                    });
                }

                glideProvaiEVede.on('run', function() {
                    updateBulletsProvaiEVede();
                });

                glideProvaiEVede.on('mount.after', function() {
                    updateBulletsProvaiEVede();
                });

                glideProvaiEVede.mount();
            }
        })
        .catch(error => {
            console.error('Erro ao carregar vídeos do Provai e Vede:', error);
        });
});
</script>
@endpush
