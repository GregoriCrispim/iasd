@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Doutores de Esperança')

@section('meta-description', 'Conheça os Doutores da Esperança da IASD Central de Brasília. Profissionais de saúde dedicados a compartilhar princípios de vida saudável.')
@section('og-title', 'Doutores da Esperança - IASD Central de Brasília')
@section('og-description', 'Saúde e esperança! Médicos adventistas compartilhando princípios de vida.')
@section('page-name', 'Doutores da Esperança')

@push('styles')
<style>

    .doutores-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 20px 20px;
    }

    .doutores-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px 40px;
        border-radius: 15px;
        margin-bottom: 30px;
        text-align: center;
    }

    /* Sem espaço entre a 1ª seção (intro) e a 2ª (identidade) */
    .doutores-container > .doutores-intro {
        margin-bottom: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .doutores-container > .identidade-section {
        margin-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .doutores-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .doutores-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .galeria-section {
        margin: 60px 0;
    }

    .galeria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .galeria-item {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .galeria-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .galeria-item img {
        max-width: 50%;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .identidade-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 15px;
        margin: 50px 0;
        color: #fff;
    }

    .identidade-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: 500;
        text-align: center;
    }

    .identidade-item {
        margin-bottom: 30px;
        padding: 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
    }

    .identidade-item h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #FFD700;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .identidade-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #f8f9fa;
    }

    .valores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .valor-card {
        background: #fff;
        border-top: 3px solid rgba(0, 51, 102, 0.20);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .valor-card h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.3em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .valor-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
    }

    .atividade-section {
        margin: 60px 0;
    }

    .atividade-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        margin-bottom: 40px;
        font-weight: 500;
        text-align: center;
    }

    .atividade-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        border-top: 3px solid rgba(0, 51, 102, 0.18);
    }

    .atividade-card h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .atividade-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
    }

    .atividade-galeria {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
        align-items: start;
    }

    .atividade-galeria img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .atividade-galeria img:hover {
        transform: scale(1.02);
    }

    .video-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    .video-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .calendario-section {
        background: linear-gradient(135deg, #003366 0%, #1b4472 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin: 50px 0;
        color: #fff;
        text-align: center;
    }

    .calendario-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #fff;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .calendario-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #f8f9fa;
        margin-bottom: 15px;
    }

    .btn-instagram {
        display: inline-block;
        background-color: #E1306C;
        color: #fff;
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
        margin: 0 10px;
    }

    .btn-instagram:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(225,48,108,0.4);
        background-color: #c1275c;
    }

    .contato-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
    }

    .contato-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 500;
    }

    .contato-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .contato-item {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        border-top: 3px solid rgba(0, 51, 102, 0.20);
    }

    .contato-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        margin-bottom: 10px;
    }

    .contato-item a {
        color: #003366;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .contato-item a:hover {
        color: #1b4472;
        text-decoration: underline;
    }

    .local-section {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 40px;
        border-radius: 15px;
        margin: 30px 0 0 0;
        color: #fff;
        text-align: center;
    }

    .local-section h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #fff;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .local-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: #f8f9fa;
    }

    .lightbox {
        --doutores-lightbox-right-gap: 0px;
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: var(--header-height);
        width: calc(100vw - var(--doutores-lightbox-right-gap));
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
        .doutores-container {
            padding: 20px 15px;
        }

        .doutores-intro {
            padding: 30px 20px;
        }

        .doutores-intro h1 {
            font-size: 2.2em;
        }

        .identidade-section,
        .calendario-section {
            padding: 40px 20px;
        }

        .atividade-section {
            margin: 44px 0;
        }

        .atividade-section h2 {
            font-size: 2.1em;
            margin-bottom: 26px;
        }

        .atividade-card {
            padding: 20px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .video-section {
            padding: 22px 16px;
            border-radius: 14px;
            margin: 36px 0;
        }

        .calendario-section h3,
        .video-section h3,
        .contato-section h3 {
            font-size: 1.8em;
            margin-bottom: 18px;
        }

        .calendario-section p {
            font-size: 1rem;
        }

        .btn-instagram {
            display: block;
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            padding: 12px 18px;
            font-size: 1rem;
        }

        .galeria-grid,
        .atividade-galeria {
            grid-template-columns: 1fr;
        }

        .valores-grid,
        .contato-grid {
            grid-template-columns: 1fr;
        }

        .identidade-section h2 {
            font-size: 2em;
            flex-wrap: wrap;
            gap: 10px;
            text-align: center;
        }

        .identidade-section h2 img {
            width: 48px !important;
            height: 48px !important;
        }

        .contato-section {
            padding: 22px 16px;
            border-radius: 14px;
            margin: 36px 0;
        }

        .contato-item {
            padding: 18px 16px;
            border-top-width: 3px;
        }

        .contato-item a {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .local-section {
            padding: 22px 16px;
            border-radius: 14px;
            margin-top: 18px;
        }

        .local-section p {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/doutores/doutores_header.webp') }}" alt="Doutores de Esperança" style="width: 100%;" fetchpriority="high" decoding="async">

<div class="doutores-container">

    <!-- Seção Introdutória -->
    <div class="doutores-intro acb-fullbleed">
        <h1 class="acb-title-serif">Doutores de Esperança</h1>
        <p>
            O Ministério Doutores de Esperança consiste em um projeto de humanização hospitalar cristão apoiado pela Ação Solidária Adventista - ASA da Igreja Adventista Central de Brasília. Idealizado e criado em 2013 em Volta Redonda/RJ pelo querido Betão, Dr Gentileza, o projeto nasceu em Brasília em fevereiro de 2018 e conta com voluntários da comunidade em geral treinados para atuar na área de humanização hospitalar, através da palhaçaria, oração e música.
        </p>
        <p>
            Seu propósito é realizar atividades junto à comunidade no ambiente hospitalar, asilo, creche e outros, valorizando cada pessoa com o amor de Deus, mediante um olhar humano e cristão. Com alegria e esperança, utiliza a paródia do palhaço como médico para transformar o ambiente, abrindo caminho para que a música e a intercessão (oração) transmitam a paz e a esperança que vêm de Jesus.
        </p>
    </div>

    <!-- Seção Identidade Corporativa -->
    <div class="identidade-section acb-fullbleed">
        <h2 style="display: flex; align-items: center; justify-content: center; gap: 15px;">
            <img src="{{ asset('img/doutores/imagem_12.webp') }}" alt="" style="width: 60px; height: 60px; object-fit: contain; border-radius: 8px;" loading="lazy" decoding="async">
            Identidade Corporativa
        </h2>

        <div class="identidade-item">
            <h3>Missão</h3>
            <p>
                Humanizar o atendimento em hospitais e instituições de saúde, levando a alegria, o acolhimento e a esperança que vêm de Deus a pacientes, familiares e profissionais. Através da arte e do humor terapêutico (palhaçaria), expressar o amor de Cristo com empatia e intercessão, tendo os valores cristãos como nossa marca.
            </p>
        </div>

        <div class="identidade-item">
            <h3>Visão</h3>
            <p>
                Ser a maior referência em humanização hospitalar cristã no Brasil, refletindo o amor de Jesus e impactando positivamente a experiência de pacientes e profissionais de saúde.
            </p>
        </div>

        <div class="identidade-item">
            <h3>Valores</h3>
            <div class="valores-grid">
                <div class="valor-card">
                    <h4>Amor ao Próximo</h4>
                    <p>Demonstrar empatia, carinho e cuidado com os pacientes, levando conforto, alegria e esperança, refletindo o amor de Jesus em cada gesto.</p>
                </div>

                <div class="valor-card">
                    <h4>Humanização da Saúde</h4>
                    <p>Tornar o ambiente hospitalar mais acolhedor e humano, ajudando a melhorar a experiência dos pacientes e seus familiares.</p>
                </div>

                <div class="valor-card">
                    <h4>Comprometimento</h4>
                    <p>Dedicação e seriedade no trabalho voluntário, garantindo um impacto positivo nas vidas dos que são atendidos.</p>
                </div>

                <div class="valor-card">
                    <h4>Alegria e Positividade</h4>
                    <p>Levar diversão, sorrisos e momentos de felicidade, ajudando a aliviar a tensão e o sofrimento no ambiente hospitalar.</p>
                </div>

                <div class="valor-card">
                    <h4>Respeito e Dignidade</h4>
                    <p>Tratar todos os pacientes, familiares e profissionais de saúde com respeito, preservando a dignidade de cada indivíduo.</p>
                </div>

                <div class="valor-card">
                    <h4>Trabalho em Equipe</h4>
                    <p>Colaborar de forma harmoniosa e eficiente com outros voluntários e profissionais de saúde, visando ao bem-estar comum.</p>
                </div>

                <div class="valor-card">
                    <h4>Voluntariado com Ética</h4>
                    <p>Seguir princípios éticos em todas as ações, respeitando a confidencialidade e as normas das instituições de saúde.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Principais Atividades -->
    <div class="atividade-section">
        <h2 class="acb-title-serif">Principais Atividades</h2>

        <!-- Atividade 1 -->
        <div class="atividade-card">
            <h3 style="display: flex; align-items: center; gap: 15px;">
                1. Visitas Humanizadas a Hospitais
            </h3>
            <p>
                Equipes de voluntários da comunidade, adventistas ou não, maiores de 16 anos, treinados, vestindo figurinos leves (estilo "palhaço solidário" não clínico, coletes coloridos de acordo com a função) levam música, a palavra, a oração e contação de histórias para crianças e adultos internados; promovem interação com familiares e profissionais de saúde.
            </p>
            <div class="atividade-galeria">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_16.webp') }}" alt="Visitas hospitalares" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_16.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_09.webp') }}" alt="Voluntários em ação" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_09.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_02.webp') }}" alt="Humanização" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_02.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_05.webp') }}" alt="Acolhimento" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_05.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_06.webp') }}" alt="Alegrando pacientes" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_06.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_10.webp') }}" alt="Equipe de voluntários" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_10.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_03.webp') }}" alt="Momentos de alegria" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_03.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_07.webp') }}" alt="Palhaços solidários" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_07.webp') }}">
            </div>
        </div>

        <!-- Atividade 2 -->
        <div class="atividade-card">
            <h3>2. Oficinas do Coração do Bem</h3>
            <p>
                Cada paciente visitado recebe um coração confecionado por voluntários da comunidade, desde criança até idoso, atuante ou não no projeto. Os corações são feitos de feltro, decorados, esterelizados e embalados individualmente. Cada coração é diferente do outro para mostrar para o paciente que ele também é único e especial, muito amado por Deus. Também são confeccionados corações especiais para as crianças/pediatria. As oficinas e o Projeto têm sido instrumentos de resgate de pessoas com depressão e isolamento, pelo senso de propósito, utilidade, pertencimento e amor ao próximo trazidos.
            </p>
            <div class="atividade-galeria">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_14.webp') }}" alt="Oficinas de corações" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_14.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_13.webp') }}" alt="Artesanato solidário" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_13.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_04.webp') }}" alt="Voluntários na confecção" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_04.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_08.webp') }}" alt="Corações de feltro" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_08.webp') }}">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_18.webp') }}" alt="Amor em cada detalhe" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_18.webp') }}">
            </div>
        </div>

        <!-- Atividade 3 -->
        <div class="atividade-card">
            <h3>3. Ações em Instituições</h3>
            <p>
                Eventos de acolhimento fora do hospital para alcançar populações vulneráveis e ampliar o impacto comunitário, realizados em asilos, orfanatos, creches e outras instituições.
            </p>
            <div class="atividade-galeria">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_15.webp') }}" alt="Ações em asilos" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_15.webp') }}">
            </div>
        </div>

        <!-- Atividade 4 -->
        <div class="atividade-card">
            <h3>4. Formação de Voluntários</h3>
            <p>
                Treinamentos periódicos que abordam técnicas de humanização, palhaçaria, ética, postura em ambientes de saúde, inteligência emocional, música, fotografia e protocolos de segurança, com emissão de certificado. As inscrições são geralmente comunicadas pelas redes locais do projeto.
            </p>
            <div class="atividade-galeria">
                <img loading="lazy" decoding="async" src="{{ asset('img/doutores/imagem_11.webp') }}" alt="Formação de voluntários" class="lightbox-trigger" data-full="{{ asset('img/doutores/imagem_11.webp') }}">
            </div>
        </div>

        <!-- Atividade 5 -->
        <div class="atividade-card">
            <h3>5. Parcerias e Mobilização Social</h3>
            <p>
                Trabalho integrado com a rede de Assistência Social Adventista, ASA, e outras organizações que facilitam acesso a hospitais e aproveitam know-how institucional para ampliar alcance e garantir práticas seguras.
            </p>
        </div>
    </div>

    {{-- Seção Vídeo (oculta temporariamente - documento do Drive inválido) --}}
    {{--
    <div class="video-section">
        <h3>Conheça Mais o Nosso Trabalho</h3>
        <div class="video-container">
            <iframe src="https://drive.google.com/file/d/1jjdQURpZyZCY4kX2nnjyg5zgMYbM7VTb/preview" allow="autoplay"></iframe>
        </div>
    </div>
    --}}

    <!-- Seção Como Participar -->
    <div class="calendario-section acb-fullbleed">
        <h3 class="acb-title-serif">Como Participar / Voluntariado</h3>
        <p>
            Se você tem mais de 16 anos e deseja ser voluntário(a), será muito bem-vindo(a)! Para participar, é necessário realizar o treinamento do grupo local e assinar o termo de responsabilidade para uso de voz e imagem.
        </p>
        <p style="margin-top: 20px;">
            Acompanhe as chamadas para formação nas redes sociais do grupo:
        </p>
        <div style="margin-top: 25px;">
            <a href="https://www.instagram.com/doutoresdeesperancabsb" target="_blank" class="btn-instagram">
                @doutoresdeesperancabsb
            </a>
        </div>
    </div>

    <!-- Seção de Contato -->
    <div class="contato-section acb-fullbleed">
        <h3 class="acb-title-serif">Contato e Redes Sociais</h3>
        <div class="contato-grid">
            <div class="contato-item">
                <p><strong>Instagram:</strong></p>
                <a href="https://www.instagram.com/doutoresdeesperancabsb" target="_blank">@doutoresdeesperancabsb</a>
            </div>

            <div class="contato-item">
                <p><strong>E-mail:</strong></p>
                <a href="mailto:doutoresdeesperancabsb@gmail.com">doutoresdeesperancabsb@gmail.com</a>
            </div>

            <div class="contato-item">
                <p><strong>Telefone:</strong></p>
                <p>(61) 99113-2590</p>
            </div>
        </div>

        <div class="local-section">
            <h4 class="acb-title-serif">Local de Reunião</h4>
            <p>IASD Central de Brasília — SGAS 611, sala Dr. de Esperança</p>
        </div>
    </div>

    <div class="lightbox" id="lightbox" aria-hidden="true">
        <span class="lightbox-close" aria-label="Fechar">&times;</span>
        <img class="lightbox-content" id="lightbox-img" src="" alt="" loading="lazy" decoding="async">
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

        lightbox.style.setProperty('--doutores-lightbox-right-gap', `${rightGap}px`);
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
