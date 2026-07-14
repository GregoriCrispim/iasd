@extends('layouts.app')

@section('title', 'IASD Central de Brasília - História Completa Desbravadores Cruzeiro do Sul')

@section('meta-description', 'Conheça a história completa do Clube de Desbravadores Cruzeiro do Sul desde 1972. Mais de 50 anos de transformação de vidas, camporis, líderes e legado.')
@section('og-title', 'História Completa - Desbravadores Cruzeiro do Sul')
@section('og-description', 'Mais de 50 anos formando jovens líderes com fé, serviço e espírito de aventura. Conheça nossa história completa desde 1972.')
@section('page-name', 'História Desbravadores')

@push('styles')
<style>
    .history-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 40px;
        box-sizing: border-box;
    }

    .history-header-wrap {
        width: 100%;
        overflow: hidden;
        aspect-ratio: 1920 / 400;
        margin-bottom: 40px;
    }

    .history-page-header-img {
        width: 100%;
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .hero-section-history {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        padding: 60px 40px;
        border-radius: 20px;
        margin-bottom: 50px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section-history::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: shine 20s infinite linear;
    }

    @keyframes shine {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .hero-content-history {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-section-history h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3.5em;
        margin-bottom: 25px;
        font-weight: 500;
        letter-spacing: .05em;
    }

    .hero-section-history .subtitle {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3rem;
        line-height: 1.8;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .hero-section-history .mission {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #D4AF37;
        margin-top: 30px;
        letter-spacing: .1em;
    }

    .section-block {
        background: white;
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .section-block h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #8B4513;
        margin-bottom: 30px;
        font-weight: 500;
        position: relative;
        padding-bottom: 15px;
        text-align: center;
    }

    .section-block h2::after {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #D2691E, #CD853F);
    }

    .section-block h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #003366;
        margin: 30px 0 15px;
    }

    .section-block p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 15px;
        text-align: justify;
    }

    .timeline-section {
        position: relative;
        padding-left: 40px;
        margin: 30px 0;
    }

    .timeline-section::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #D2691E 0%, #CD853F 100%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .timeline-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(210, 105, 30, 0.2);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -49px;
        top: 25px;
        width: 16px;
        height: 16px;
        background: #D2691E;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #D2691E;
    }

    .timeline-item__year {
        display: inline-block;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.2em;
        margin-bottom: 10px;
    }

    .timeline-item__content {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
    }

    .info-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .info-card-highlight {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px;
        border-radius: 15px;
        border-left: 5px solid #D2691E;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .info-card-highlight:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.2);
    }

    .info-card-highlight h4 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #8B4513;
        margin-bottom: 15px;
    }

    .info-card-highlight p {
        font-size: 1rem;
        line-height: 1.6;
        color: #666;
        margin: 0;
        text-align: left;
    }

    .leader-spotlight {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        margin: 40px 0;
        position: relative;
        overflow: hidden;
    }

    .leader-spotlight::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: shine 15s infinite linear;
    }

    .leader-spotlight__content {
        position: relative;
        z-index: 1;
    }

    .leader-spotlight__icon {
        font-size: 4em;
        margin-bottom: 20px;
    }

    .leader-spotlight__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        margin-bottom: 25px;
    }

    .leader-spotlight__text {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .leader-spotlight__quote {
        background: rgba(255, 255, 255, 0.2);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #D4AF37;
        font-style: italic;
        margin: 25px 0;
    }

    .campori-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .campori-card {
        background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
        padding: 25px;
        border-radius: 12px;
        border-left: 4px solid #D2691E;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .campori-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(210, 105, 30, 0.2);
    }

    .campori-card__year {
        display: inline-block;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.1em;
        margin-bottom: 10px;
    }

    .campori-card__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.3em;
        color: #8B4513;
        margin: 10px 0;
    }

    .campori-card__details {
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
    }

    .directors-list {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
        margin: 30px 0;
    }

    .directors-list__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.8em;
        color: #8B4513;
        margin-bottom: 20px;
    }

    .directors-list__items {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .directors-list__item {
        background: white;
        padding: 12px 15px;
        border-radius: 8px;
        border-left: 3px solid #D2691E;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #333;
    }

    .directors-list__year {
        font-weight: 700;
        color: #8B4513;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #D2691E 0%, #CD853F 100%);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        transition: transform 0.3s, box-shadow 0.3s;
        margin: 20px 0;
    }

    .back-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(210, 105, 30, 0.4);
    }

    .back-button::before {
        content: '←';
        font-size: 1.2em;
    }

    .highlight-box {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe9a1 100%);
        padding: 25px;
        border-radius: 15px;
        margin: 30px 0;
        border-left: 5px solid #ffc107;
    }

    .highlight-box__title {
        font-family: 'Bebas neue', sans-serif;
        font-size: 1.5em;
        color: #856404;
        margin-bottom: 15px;
    }

    .highlight-box__text {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #856404;
        line-height: 1.6;
    }

    .value-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(210, 105, 30, 0.25);
    }

    .identity-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .identity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.2);
    }

    .pioneer-item {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .pioneer-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    @media (max-width: 768px) {
        .hero-section-history {
            padding: 40px 20px;
        }

        .hero-section-history h1 {
            font-size: 2.5em;
        }

        .section-block {
            padding: 30px 20px;
        }

        .timeline-section {
            padding-left: 30px;
        }

        .timeline-item::before {
            left: -39px;
        }

        .info-cards-grid,
        .campori-grid {
            grid-template-columns: 1fr;
        }

        .directors-list__items {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Header Image -->
<div class="history-header-wrap">
    <img src="{{ asset('img/noticias/desbravadores-1-header.jpeg') }}" alt="História Desbravadores Cruzeiro do Sul" class="history-page-header-img">
</div>

<!-- Hero Section -->
<div class="hero-section-history acb-fullbleed">
    <div class="hero-content-history">
        <h1>História Completa</h1>
        <p class="subtitle">Mais de 50 anos transformando vidas e formando líderes cristãos no Distrito Federal e além</p>
        <p class="mission">"TEMOS UMA MISSÃO... LEVAR A MENSAGEM DO ADVENTO A TODO O MUNDO EM NOSSA GERAÇÃO!"</p>
    </div>
</div>

<div class="history-container">
    <!-- Botão Voltar -->
    <a href="/desbravadores-cruzeiro-do-sul" class="back-button">
        Voltar para Desbravadores
    </a>

    <!-- Introdução e Missão -->
    <div class="section-block">
        <h2>Nossa Identidade</h2>

        <div class="info-cards-grid">
            <div class="info-card-highlight identity-card" style="text-align: center;">
                <div style="font-size: 3em; margin-bottom: 15px;">🎯</div>
                <h4 style="margin-bottom: 15px; color: #003366;">Nossa Missão</h4>
                <p style="text-align: center; color: #8B4513; font-weight: 600; font-size: 1.1em; margin: 0;">"Levar a mensagem do advento a todo o mundo em nossa geração!"</p>
            </div>

            <div class="info-card-highlight identity-card" style="text-align: center;">
                <div style="font-size: 3em; margin-bottom: 15px;">🌟</div>
                <h4 style="margin-bottom: 15px; color: #003366;">Nosso Propósito</h4>
                <p style="text-align: center;">Formar jovens líderes com fé, serviço e espírito de aventura, preparando-os para serem cidadãos exemplares em suas comunidades.</p>
            </div>

            <div class="info-card-highlight identity-card" style="text-align: center;">
                <div style="font-size: 3em; margin-bottom: 15px;">🏆</div>
                <h4 style="margin-bottom: 15px; color: #003366;">Nosso Legado</h4>
                <p style="text-align: center;">Mais de 50 anos transformando vidas e formando gerações de desbravadores comprometidos com os princípios cristãos.</p>
            </div>
        </div>

        <div class="highlight-box" style="margin-top: 40px;">
            <h3 class="highlight-box__title">Pioneirismo e Liderança no Distrito Federal</h3>
            <p class="highlight-box__text">O Cruzeiro do Sul teve grande importância no movimento dos Desbravadores dentro do Distrito Federal. Além do clube de Sobradinho, vários outros clubes foram fundados pelos líderes formados no Cruzeiro do Sul.</p>
            <p class="highlight-box__text">Devido ao fato da sede da Associação (ABC) ser sediada em Goiânia (até a criação da APlaC), distante de Brasília, o Cruzeiro do Sul foi durante algum tempo responsável por boa parte da produção e distribuição de material para os clubes do Distrito Federal, como:</p>
            <div style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div class="pioneer-item" style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px; border-left: 3px solid #D4AF37;">
                    <strong>📚</strong> Apostilas de classes e especialidades
                </div>
                <div class="pioneer-item" style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px; border-left: 3px solid #D4AF37;">
                    <strong>📰</strong> Boletins informativos
                </div>
                <div class="pioneer-item" style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px; border-left: 3px solid #D4AF37;">
                    <strong>📋</strong> Manuais para Diretoria
                </div>
                <div class="pioneer-item" style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px; border-left: 3px solid #D4AF37;">
                    <strong>👕</strong> Material para uniforme
                </div>
            </div>
        </div>
    </div>

    <!-- Nossos Valores -->
    <div class="section-block" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <h2>Nossos Valores</h2>
        <p style="text-align: center; max-width: 800px; margin: 0 auto 30px; color: #555;">Nossa missão é desenvolver adolescentes (10–15 anos) integralmente — físico, mental e espiritual — por meio de atividades ao ar livre, serviço cristão e liderança. Nossos valores são baseados nos princípios cristãos, promovendo o desenvolvimento harmonioso de todas as faculdades humanas.</p>

        <div style="text-align: center; margin-bottom: 30px;">
            <p style="color: #8B4513; font-family: 'Bebas neue', sans-serif; font-size: 1.4em; font-weight: 600; margin: 0; text-align: center; padding: 20px 0;">Valorizamos fé ativa, disciplina, amizade e preparo para a vida.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 30px;">
            <div class="value-card" style="background: white; padding: 35px 25px; border-radius: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                <div style="font-size: 3em; margin-bottom: 20px;">⛪</div>
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.6em; color: #003366; margin: 0 0 15px;">Fé</h3>
                <p style="font-size: 0.95rem; color: #666; line-height: 1.6; margin: 0; text-align: center;">Desenvolvimento espiritual e conexão com Deus através das atividades e ensinamentos cristãos.</p>
            </div>

            <div class="value-card" style="background: white; padding: 35px 25px; border-radius: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                <div style="font-size: 3em; margin-bottom: 20px;">🤝</div>
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.6em; color: #003366; margin: 0 0 15px;">Serviço</h3>
                <p style="font-size: 0.95rem; color: #666; line-height: 1.6; margin: 0; text-align: center;">Compromisso com a comunidade e o próximo através de ações práticas e evangelismo.</p>
            </div>

            <div class="value-card" style="background: white; padding: 35px 25px; border-radius: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                <div style="font-size: 3em; margin-bottom: 20px;">🥾</div>
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.6em; color: #003366; margin: 0 0 15px;">Aventura</h3>
                <p style="font-size: 0.95rem; color: #666; line-height: 1.6; margin: 0; text-align: center;">Atividades ao ar livre, acampamentos e superação de desafios para crescimento pessoal.</p>
            </div>

            <div class="value-card" style="background: white; padding: 35px 25px; border-radius: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                <div style="font-size: 3em; margin-bottom: 20px;">👥</div>
                <h3 style="font-family: 'Bebas neue', sans-serif; font-size: 1.6em; color: #003366; margin: 0 0 15px;">Liderança</h3>
                <p style="font-size: 0.95rem; color: #666; line-height: 1.6; margin: 0; text-align: center;">Formação de jovens líderes cristãos preparados para servir com excelência.</p>
            </div>
        </div>
    </div>

    <!-- Evolução das Sedes -->
    <div class="section-block">
        <h2>Evolução das Nossas Sedes</h2>

        <div class="timeline-section">
            <div class="timeline-item">
                <span class="timeline-item__year">Início - 1972</span>
                <div class="timeline-item__content">
                    <strong>A Primeira Sede</strong><br>
                    A primeira sede do Clube foi um barracão de madeira nos fundos da Igreja. Com isso iniciava-se uma busca para que o clube se tornasse um lugar acolhedor para nossas crianças.
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-item__year">1979</span>
                <div class="timeline-item__content">
                    <strong>Crescimento e Mudança</strong><br>
                    O Clube passou a compartilhar com o Coral uma sala nas dependências da Igreja ao lado da Sala Pastoral. Conforme crescia, fez-se necessário um local mais amplo e o Clube se instalou em uma sala no subsolo da Igreja.
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-item__year">1995</span>
                <div class="timeline-item__content">
                    <strong>Expansão</strong><br>
                    O Clube transferiu sua sede para uma sala do prédio onde funcionava até então a Escola Adventista de Brasília, quando esta se mudou para seu novo prédio.
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-item__year">1999</span>
                <div class="timeline-item__content">
                    <strong>Nova Localização</strong><br>
                    O Clube ganhou uma nova sede ao lado da Divisão Sul-Americana.
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-item__year">2019</span>
                <div class="timeline-item__content">
                    <strong>Sede Moderna e Completa</strong><br>
                    A igreja inaugurou a nova sede do clube, com salas para cada uma das classes, auditório, museu, biblioteca, cozinha e refeitório, área completa para aventureiros e depósitos e uma nova área destinada ao paredão de escalada. Uma área útil de aproximadamente 900m².
                </div>
            </div>
        </div>
    </div>

    <!-- Os Aventureiros -->
    <div class="section-block">
        <h2>Os Aventureiros</h2>
        <p>O Clube Cruzeiro do Sul foi o pioneiro na criação de um clube especialmente desenvolvido para nossos anjos menores de 10 anos.</p>

        <div class="info-cards-grid">
            <div class="info-card-highlight">
                <h4>🌟 Fundação</h4>
                <p>O Clube de "Mirins" (hoje Clube de Aventureiros Cruzeiro do Sul Mirim) foi criado em 1º de março de 1976.</p>
            </div>

            <div class="info-card-highlight">
                <h4>🎖️ Identidade Própria</h4>
                <p>Já possuía seu próprio uniforme, hino, voto e lei em sua fundação.</p>
            </div>

            <div class="info-card-highlight">
                <h4>📚 Programa Ativo</h4>
                <p>Em seu programa de atividades, os Mirins desenvolviam as classes progressivas preliminares.</p>
            </div>
        </div>
    </div>

    <!-- Clube de Líderes -->
    <div class="section-block">
        <h2>Clube de Líderes</h2>
        <p>Em 30 de março de 1984, foi criado o "Clube de Conquistadores", que tinha como membros os jovens acima de 15 anos visando a formação de futuros Líderes.</p>

        <div class="highlight-box">
            <h3 class="highlight-box__title">Mais Uma Vez Pioneiros</h3>
            <p class="highlight-box__text">Mais uma vez o clube Cruzeiro do Sul seria pioneiro em nossa região, orgulhando-se de cada líder formado para a honra e glória de Deus.</p>
        </div>
    </div>

    <!-- Participação em Camporis -->
    <div class="section-block">
        <h2>Participação em Camporis Internacionais</h2>
        <p>Desde sua fundação o Clube tem participado de todos os Camporis de Missão, Associação, União e Divisão.</p>

        <div class="campori-grid">
            <div class="campori-card">
                <span class="campori-card__year">1989</span>
                <h3 class="campori-card__title">Argentina</h3>
                <p class="campori-card__details">IV Campori de Conquistadores da União Austral, em Bahia Blanca, Argentina. Participação como convidado.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2009</span>
                <h3 class="campori-card__title">EUA - Oshkosh</h3>
                <p class="campori-card__details">Camporee Mundial "COURAGE TO STAND". Participação com outros 4 clubes do Brasil.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2014</span>
                <h3 class="campori-card__title">EUA - Oshkosh</h3>
                <p class="campori-card__details">International Pathfinder Camporee "FOREVER FAITHFUL". Mais uma representação internacional.</p>
            </div>
        </div>
    </div>

    <!-- Tio Alcyr -->
    <div class="leader-spotlight">
        <div class="leader-spotlight__content">
            <div class="leader-spotlight__icon">⭐</div>
            <h2 class="leader-spotlight__title">Tio Alcyr Osório Pinto</h2>
            <p class="leader-spotlight__text">Não poderíamos abrir a galeria de diretores do clube sem antes falarmos brevemente do Líder Alcyr Osório Pinto ou simplesmente Tio Alcyr (como chamam os desbravadores).</p>

            <p class="leader-spotlight__text">Este grande Líder destaca-se e representa os vários líderes valorosos que serviram e servem nosso querido clube de forma extraordinária. Tio Alcyr também é reconhecido como um dos mais importantes líderes e pioneiros no ministério dos Desbravadores no Distrito Federal e no Brasil.</p>

            <div class="leader-spotlight__quote">
                <p>Este breve histórico faz justiça ao Líder que nos deixou um legado de amor ao próximo, trabalho incansável nas boas obras do Senhor, organização material impecável e extremo cuidado com nossas crianças.</p>
            </div>

            <p class="leader-spotlight__text"><strong>Chegada em Brasília:</strong> O querido Tio Alcyr chegou a Brasília no ano de 1976, tendo já exercido sua liderança como diretor de vários ministérios da igreja no Rio de Janeiro e no Paraná.</p>

            <p class="leader-spotlight__text"><strong>Início da Jornada:</strong> Iniciou suas atividades na Igreja Central de Brasília como diretor da escola sabatina e colaborador do Clube de Desbravadores Cruzeiro do Sul, sendo convidado ao final do ano 1978 a assumir o cargo de diretor.</p>

            <p class="leader-spotlight__text"><strong>Transformação:</strong> O Clube (fundado em 1972) não possuía na época quase nenhum material nem sede própria. Sendo assim, tão logo assumiu a direção, Alcyr lutou de forma incansável para estruturar o Clube, realizando campanhas para levantar recursos para adquirir barracas, material de cozinha e acampamento.</p>

            <p class="leader-spotlight__text"><strong>Inovação:</strong> Inovador, nesse primeiro ano também criou o característico uniforme de atividades do Clube, com a camiseta branca com o triângulo vermelho, bermuda caqui e o meião cinza.</p>

            <p class="leader-spotlight__text"><strong>Primeiro Grande Resultado:</strong> Dessa forma, ao final de 1979 o Clube Cruzeiro do Sul conseguiu participar do I Campori da Unisul (primeiro campori de União do Brasil), no Paraná, alcançando já a 2ª colocação entre os clubes do evento.</p>
        </div>
    </div>

    <!-- Lista de Diretores -->
    <div class="section-block">
        <h2>Galeria de Diretores</h2>
        <p>O Pastor José Maria é o fundador do Clube de desbravadores Cruzeiro do Sul e foi diretor deste clube nos anos de 1972 e 1973, sendo sucedido por uma linha de líderes dedicados:</p>

        <div class="directors-list">
            <h3 class="directors-list__title">Nossos Diretores Ao Longro dos Anos</h3>
            <div class="directors-list__items">
                <div class="directors-list__item">
                    <span class="directors-list__year">1974:</span> Walter Lúcio Costa Mendes ("Waltinho")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1975:</span> Daniel Martins de Oliveira ("Danielzinho")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1976-1977:</span> Adelmo Castro de Jesus ("Paganinni")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1978:</span> Walter Lúcio Costa Mendes
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1979-1990:</span> Alcyr Osório Pinto (12 anos consecutivos)
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1991:</span> Roberto Araújo ("Robertinho") / Carlos Ubirajara Amorim ("Bira")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1992:</span> Lins Alves de Miranda
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1993-1994:</span> Hélio Vieira Cardoso / Carlos Ubirajara Amorim ("Bira")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1995:</span> Carlos Ubirajara Amorim ("Bira") / Milton Arno Luebke ("Miltão")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1996-1997:</span> Marcos Agostinho / Everson Georgio Costa
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">1999-2002:</span> Rone Toledo / Ivanilde Bezerra Macedo / Alcyr Osório Pinto
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">2003-2014:</span> Alcyr Osório Pinto / Marcos Agostinho
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">2015-2016:</span> Carlos Ubirajara Amorim ("Bira")
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">2017-2018:</span> Gilvan Farias / Bruno Diniz
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">2019-2022:</span> Bruno Diniz
                </div>
                <div class="directors-list__item">
                    <span class="directors-list__year">2023-2025:</span> Rozi Santos Manzi
                </div>
            </div>

            <div style="margin-top: 30px; padding: 20px; background: #fff8e7; border-radius: 10px; border-left: 4px solid #D4AF37;">
                <p style="margin: 0; font-family: 'Roboto', sans-serif; font-size: 1rem; color: #666; text-align: center;">
                    <strong>Destaques Especiais:</strong> Até 2017 tivemos a figura de diretor geral representada pelo Diretor Alcyr Osório (Tio Alcyr) - 38 anos como diretor Geral do Clube Cruzeiro do Sul.
                </p>
            </div>
        </div>
    </div>

    <!-- Camporis em que Estivemos Presentes -->
    <div class="section-block">
        <h2>Camporis em que Estivemos Presentes</h2>
        <p>Desde sua fundação o Clube tem participado de todos os Camporis de Missão, Associação, União e Divisão, além de ter sido convidado a participar do IV Campori de Conquistadores da União Austral, na cidade de Bahia Blanca à Argentina em 1989.</p>

        <div class="campori-grid">
            <div class="campori-card">
                <span class="campori-card__year">1979</span>
                <h3 class="campori-card__title">I Campori da UNISUL</h3>
                <p class="campori-card__details">1º a 4 de novembro - Vila Velha (PR). Primeiro campori de União do Brasil. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1980</span>
                <h3 class="campori-card__title">I Campori da Missão Brasil Central</h3>
                <p class="campori-card__details">5 a 7 de setembro - Brasília (DF). Coordenado pelo Pastor Jason McCracken. O Clube Mirim participou como convidado. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1981</span>
                <h3 class="campori-card__title">II Campori da UNISUL</h3>
                <p class="campori-card__details">28 de outubro a 2 de novembro - Florianópolis (SC). Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1982</span>
                <h3 class="campori-card__title">II Campori da Missão Brasil Central</h3>
                <p class="campori-card__details">18 a 29 de junho - Brasília (DF) no CATRE. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1983</span>
                <h3 class="campori-card__title">I Campori da Divisão Sul-Americana</h3>
                <p class="campori-card__details">28 de dezembro a 4 de janeiro - Foz do Iguaçu (PR). Primeiro Campori Sul-Americano. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1984</span>
                <h3 class="campori-card__title">III Campori da ABC</h3>
                <p class="campori-card__details">7 a 9 de setembro - Brasília (DF). Criação da Associação Brasil Central (ABC) com sede em Goiânia. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1986</span>
                <h3 class="campori-card__title">IV Campori da ABC</h3>
                <p class="campori-card__details">4 a 11 de outubro - Brasília (DF). Parque da Cidade de Brasília. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1987</span>
                <h3 class="campori-card__title">V Campori da ABC & I Campori da UCB</h3>
                <p class="campori-card__details">V Campori ABC: 9 a 12 outubro - Formosa (GO). I Campori UCB: 28 janeiro a 3 fevereiro - Avaré (SP). Tema: "Ele está ao Leme". Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1988</span>
                <h3 class="campori-card__title">VI Campori da ABC</h3>
                <p class="campori-card__details">28 a 30 de outubro - Formosa (GO). Campo de Treinamento do Exército Brasileiro. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1989</span>
                <h3 class="campori-card__title">IV Campori da União Austral (Argentina)</h3>
                <p class="campori-card__details">5 a 11 de janeiro - Bahia Blanca, Argentina. Participação como convidado. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1991</span>
                <h3 class="campori-card__title">VII Campori da ABC & I Acampamento Festivo</h3>
                <p class="campori-card__details">Anápolis (GO) - IABC. VII Campori ABC e I Acampamento Festivo. Diretor: Carlos Ubirajara ("Bira").</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1992</span>
                <h3 class="campori-card__title">II Campori da UCB & VIII Campori da ABC</h3>
                <p class="campori-card__details">II Campori UCB: 6 a 11 julho - Ilha Solteira (SP). Tema: "Além do Rio". VIII Campori ABC: IABC Anápolis (GO). Diretores: Lins Alves / Carlos Ubirajara.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1994</span>
                <h3 class="campori-card__title">IX Campori da ABC & II Campori Divisão Sul-Americana</h3>
                <p class="campori-card__details">IX Campori ABC: Formosa (GO). Tema: "Amigos em busca de Cristo". II Campori DSA: Ponta Grossa (PR). Tema: "Na Trilha dos Pioneiros". Diretor: Carlos Ubirajara Amorim.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1996</span>
                <h3 class="campori-card__title">III Campori da UCB & II Campori Interclubes</h3>
                <p class="campori-card__details">III Campori UCB: 2 a 7 julho - Brasília (DF). Tema: "Unidos na Esperança". II Campori Interclubes APlaC: Brasília (DF). Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1997</span>
                <h3 class="campori-card__title">III Campori Interclubes & I Campori da APlaC</h3>
                <p class="campori-card__details">III Campori Interclubes Mindalja/APlaC: Brasília (DF). I Campori APlaC: Palmas (TO). Tema: "Navegando no Rio". Diretor: Everson Georgio Costa.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1998</span>
                <h3 class="campori-card__title">XII Campori da ABC & I Campori Sul-Americano de Líderes</h3>
                <p class="campori-card__details">XII Campori ABC: Anápolis (GO). Tema: "Rumo ao Porto Seguro". I Campori Sul-Americano de Líderes: Pucon, Chile (janeiro). Representantes: Liney Reis e Roberto Suguino. Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">1999</span>
                <h3 class="campori-card__title">II Campori da APlaC</h3>
                <p class="campori-card__details">12 a 15 de novembro - Luziânia (GO). Tema: "Jesus meu Melhor Amigo". Diretor: Roni Toledo.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2000</span>
                <h3 class="campori-card__title">I Campori do Tocantins & Jubileu de Ouro</h3>
                <p class="campori-card__details">I Campori Tocantins: 4 a 6 agosto - Gurupi (TO). Tema: "Brilhando com o Amigo Jesus". Jubileu de Ouro: 29 abril a 1º maio - Formosa (GO). Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2001</span>
                <h3 class="campori-card__title">XIV Campori da ABC</h3>
                <p class="campori-card__details">Goiânia (GO). Tema: "Aventuras no Ar". Diretor: Ivanilde Bezerra Macedo.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2002</span>
                <h3 class="campori-card__title">IV Campori da UCB</h3>
                <p class="campori-card__details">4 a 9 julho - UNASP Engenheiro Coelho (SP). Tema: "Heróis de Hoje". Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2003</span>
                <h3 class="campori-card__title">III Campori da APlaC</h3>
                <p class="campori-card__details">10 a 13 outubro - Parque de Exposições de Brasília (DF). Tema: "Unidos pelo Amigo Jesus". Diretor: Alcyr Osório.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2004</span>
                <h3 class="campori-card__title">Surge União Centro-Oeste Brasileira (UCOB)</h3>
                <p class="campori-card__details">18 de outubro de 2004. Criação da UCOB com sede em Brasília (DF). Diretor: Adriana Vianna de Oliveira.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2005</span>
                <h3 class="campori-card__title">III Campori da Divisão Sul-Americana</h3>
                <p class="campori-card__details">11 a 16 janeiro - Santa Helena (PR). Mais de 16 mil inscritos. Tema: "Fonte de Esperança". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2006</span>
                <h3 class="campori-card__title">IV Campori da APlaC</h3>
                <p class="campori-card__details">12 a 15 outubro - Parque de Exposições de Brasília (DF). Tema: "Unidos na Fé". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2007</span>
                <h3 class="campori-card__title">I Campori da UCOB</h3>
                <p class="campori-card__details">11 a 15 julho - IABC Anápolis (GO). Tema: "Mais que Vencedor". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2009</span>
                <h3 class="campori-card__title">I Campori Integrado & Camporee Mundial</h3>
                <p class="campori-card__details">I Campori Integrado APlaC/ABC: 11 a 14 junho - CATRE Brasília (DF). Tema: "Coragem pra Permanecer". Camporee Mundial: 11 a 15 agosto - Oshkosh, EUA. Tema: "Courage to Stand". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2011</span>
                <h3 class="campori-card__title">VI Campori da APlaC</h3>
                <p class="campori-card__details">11 a 15 novembro - Parque de Exposições de Brasília (DF). Tema: "Aventuras No Deserto". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2012</span>
                <h3 class="campori-card__title">II Campori da UCOB</h3>
                <p class="campori-card__details">4 a 9 setembro - Parque da Cidade de Brasília, Sarah Kubitschek. Tema: "TRILHA DA ESPERANÇA". Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2014</span>
                <h3 class="campori-card__title">International Pathfinder Camporee</h3>
                <p class="campori-card__details">Oshkosh, EUA. "2014 International Pathfinder Camporee FOREVER FAITHFUL". Participação internacional. Diretor: Marcos Agostinho.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2017</span>
                <h3 class="campori-card__title">III Campori da UCOB</h3>
                <p class="campori-card__details">6 a 10 setembro - Rondonópolis, Mato Grosso. Tema: "ESCOLHIDO PARA A MISSÃO". Diretor Geral: Alcyr Osório. Diretor de Desbravadores: Gilvan Farias.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2018</span>
                <h3 class="campori-card__title">II Campori Online de Desbravadores DSA</h3>
                <p class="campori-card__details">Setembro. Realizado de forma virtual com presença física do clube nas transmissões direto da sede da DAS em Brasília. Tema: "VAMOS COM TODOS". Diretor: Bruno Diniz.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2019</span>
                <h3 class="campori-card__title">V Campori Sul-Americano de Desbravadores DSA</h3>
                <p class="campori-card__details">Janeiro - Parque do Peão em Barretos, São Paulo. Tema: "A Melhor Aventura". Diretor: Bruno Diniz.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2020</span>
                <h3 class="campori-card__title">II Campori Online da APLAC</h3>
                <p class="campori-card__details">Setembro. Ambiente virtual. Tema: "ALEGRIA EM SERVIR". Diretor: Bruno Diniz. Diretores Temporários: Gabriel Manzi e Rozilene Manzi.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2022</span>
                <h3 class="campori-card__title">Campori da APLAC</h3>
                <p class="campori-card__details">Junho - CATRE. Tema: "Imunes como Jésus". Diretor: Bruno Diniz.</p>
            </div>

            <div class="campori-card">
                <span class="campori-card__year">2024</span>
                <h3 class="campori-card__title">Campori da UCOB</h3>
                <p class="campori-card__details">Julho - IABC. Tema: "O Segredo". Diretora: Rozi Santos Manzi.</p>
            </div>
        </div>
    </div>

    <!-- Conclusão -->
    <div class="section-block" style="background: linear-gradient(135deg, #003366 0%, #001531 100%); color: white;">
        <h2 style="color: white;">A História Continua...</h2>
        <p style="color: rgba(255,255,255,0.9);">O Clube de Desbravadores Cruzeiro do Sul continua escrevendo sua história, formando gerações de jovens líderes comprometidos com os princípios cristãos e preparando-os para serem cidadãos exemplares e líderes em suas comunidades.</p>

        <div style="margin-top: 30px; padding: 25px; background: rgba(255,255,255,0.1); border-radius: 15px; border-left: 4px solid #D4AF37;">
            <p style="margin: 0; font-family: 'Bebas neue', sans-serif; font-size: 1.5em; color: #D4AF37; text-align: center; letter-spacing: .1em;">
                "QUE DEUS NOS ABENÇOE EM NOSSA JORNADA! SALVAR DO PECADO E GUIAR NO SERVIÇO."
            </p>
        </div>
    </div>

    <!-- Botão Voltar -->
    <a href="/desbravadores-cruzeiro-do-sul" class="back-button">
        Voltar para Desbravadores
    </a>
</div>
@endsection