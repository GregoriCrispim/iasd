@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Profecias Bíblicas')

@section('meta-description', 'Estude as profecias bíblicas na IASD Central de Brasília. Profecias de Daniel, Apocalipse e livros proféticos. Aprofunde seu conhecimento bíblico.')
@section('og-title', 'Profecias Bíblicas - IASD Central de Brasília')
@section('og-description', 'Descubra o fascinante mundo das profecias bíblicas e o que elas revelam sobre os últimos eventos.')
@section('page-name', 'Profecias')

@push('styles')
<style>
    /* Ajuste suave para rolagem */
    html { scroll-behavior: smooth; }

    .profecias-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .hero-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .hero-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    /* Introdução */
    .intro-section {
        padding: 24px 16px;
        max-width: 896px;
        margin: 0 auto;
    }

    .intro-lead {
        font-family: 'Playfair Display', serif;
        font-size: 1.5em;
        color: #1f2937;
        font-style: italic;
        border-left: 4px solid #3b82f6;
        padding-left: 16px;
        margin-bottom: 32px;
    }

    .intro-text {
        color: #4b5563;
        font-size: 1.125em;
        line-height: 1.8;
    }

    .intro-text p {
        margin-bottom: 16px;
    }

    /* Por Que Estudar */
    .why-study-section {
        background: #fff;
        padding: 24px 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .why-study-content {
        max-width: 1024px;
        margin: 0 auto;
    }

    .section-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2em;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }

    .section-header p {
        font-size: 1.125em;
        color: #6b7280;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        padding: 24px;
        background: #f9fafb;
        border-radius: 8px;
        transition: box-shadow 0.3s;
    }

    .benefit-item:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .benefit-icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 6px;
        background: #3b82f6;
        color: #fff;
    }

    .benefit-text {
        margin-left: 16px;
    }

    .benefit-text h3 {
        font-size: 1.125em;
        font-weight: 600;
        color: #111827;
        margin-bottom: 8px;
    }

    .benefit-text p {
        font-size: 1em;
        color: #6b7280;
    }

    /* Guias de Estudo */
    .guides-section {
        background: #f9fafb;
        padding: 48px 16px;
    }

    .guides-content {
        max-width: 1024px;
        margin: 0 auto;
    }

    .guides-header {
        text-align: center;
        margin-bottom: 64px;
    }

    .guides-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5em;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }

    .guides-header p {
        font-size: 1.125em;
        color: #4b5563;
        max-width: 48rem;
        margin: 0 auto 16px;
    }

    .guides-header .highlight {
        color: #2563eb;
        font-weight: 600;
    }

    .guide-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 48px;
        display: flex;
        flex-direction: column;
    }

    .guide-card-blue .guide-sidebar {
        background: #1e3a8a;
    }

    .guide-card-purple .guide-sidebar {
        background: #581c87;
    }

    .guide-sidebar {
        width: 100%;
        color: #fff;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .guide-sidebar svg {
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
    }

    .guide-card-blue .guide-sidebar svg {
        color: #93c5fd;
    }

    .guide-card-purple .guide-sidebar svg {
        color: #d8b4fe;
    }

    .guide-sidebar h3 {
        font-size: 1.5em;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .guide-sidebar p {
        font-size: 1em;
        opacity: 0.9;
    }

    .guide-card-blue .guide-sidebar p {
        color: #bfdbfe;
    }

    .guide-card-purple .guide-sidebar p {
        color: #e9d5ff;
    }

    .guide-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .guide-content p {
        color: #4b5563;
        line-height: 1.625;
        font-size: 1em;
        margin-bottom: 24px;
    }

    .guide-actions {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .action-row {
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
        padding-top: 8px;
        border-top: 1px solid #f3f4f6;
    }

    .action-row:first-child {
        padding-top: 0;
        border-top: none;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border: 1px solid transparent;
        font-size: 1em;
        font-weight: 500;
        border-radius: 6px;
        color: #fff;
        background: #4f46e5;
        text-decoration: none;
        transition: background 0.15s;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background: #4338ca;
    }

    .btn-primary.btn-purple {
        background: #9333ea;
    }

    .btn-primary.btn-purple:hover {
        background: #7e22ce;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border: 1px solid transparent;
        font-size: 1em;
        font-weight: 500;
        border-radius: 6px;
        color: #4338ca;
        background: #e0e7ff;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-secondary:hover {
        background: #c7d2fe;
    }

    .btn-secondary.btn-purple-secondary {
        color: #6b21a8;
        background: #f3e8ff;
    }

    .btn-secondary.btn-purple-secondary:hover {
        background: #e9d5ff;
    }

    .btn-primary svg,
    .btn-secondary svg {
        width: 20px;
        height: 20px;
        margin-right: 8px;
    }

    .action-hint {
        font-size: 0.875em;
        color: #6b7280;
    }

    /* Conclusão */
    .cta-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: #fff;
        padding: 48px 16px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
        opacity: 0.2;
    }

    .cta-bg-blob-1 {
        position: absolute;
        top: -96px;
        left: -96px;
        width: 256px;
        height: 256px;
        border-radius: 50%;
        background: #3b82f6;
        filter: blur(64px);
    }

    .cta-bg-blob-2 {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 384px;
        height: 384px;
        border-radius: 50%;
        background: #9333ea;
        filter: blur(64px);
    }

    .cta-content {
        position: relative;
        z-index: 10;
        max-width: 768px;
        margin: 0 auto;
    }

    .cta-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5em;
        font-weight: 700;
        margin-bottom: 24px;
    }

    .cta-section p {
        font-size: 1.125em;
        color: #dbeafe;
        line-height: 1.625;
        margin-bottom: 32px;
    }

    .cta-section .emphasis {
        font-weight: 600;
        color: #fff;
    }

    .cta-quote {
        display: inline-block;
        border: 1px solid #60a5fa;
        border-radius: 8px;
        padding: 24px;
        background: rgba(30, 64, 128, 0.5);
        backdrop-filter: blur(4px);
    }

    .cta-quote p {
        font-family: 'Playfair Display', serif;
        font-size: 1.125em;
        font-style: italic;
        color: #bfdbfe;
        margin: 0;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .hero-section {
            padding: 30px 20px;
        }

        .hero-section h1 {
            font-size: 2.2em;
        }
    }

    @media (min-width: 768px) {
        .hero-section h1 {
            font-size: 3em;
        }

        .intro-lead {
            font-size: 2em;
        }

        .section-header h2 {
            font-size: 2.25em;
        }

        .benefits-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
        }

        .guides-header h2 {
            font-size: 3em;
        }

        .guide-card {
            flex-direction: row;
        }

        .guide-sidebar {
            width: 33.333%;
            padding: 32px;
        }

        .guide-sidebar svg {
            width: 64px;
            height: 64px;
        }

        .guide-sidebar h3 {
            font-size: 2em;
        }

        .guide-content {
            width: 66.667%;
            padding: 32px 40px;
        }

        .action-row {
            flex-direction: row;
            align-items: center;
        }

        .cta-section {
            padding: 80px 16px;
        }

        .cta-section h2 {
            font-size: 3em;
        }

        .cta-section p {
            font-size: 1.25em;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/profecias/profecias_header.webp') }}" alt="Profecias Bíblicas" style="width: 100%;" fetchpriority="high" decoding="async">

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Desvende o Futuro: Uma Mensagem de Esperança</h1>
            <p>
                Descubra a clareza e a paz que as profecias bíblicas oferecem em meio à incerteza dos nossos tempos.
            </p>
        </div>
    </section>

    <!-- Introdução -->
    <section class="intro-section">
        <p class="intro-lead">
            "Em meio a tanta agitação, você já olhou para o mundo ao seu redor — as notícias, os conflitos, as incertezas — e se perguntou para onde a humanidade está caminhando?"
        </p>
        <div class="intro-text">
            <p>
                Em meio a tantas perguntas, existe uma fonte de clareza e esperança: <strong>a profecia bíblica</strong>.
            </p>
            <p>
                Longe de serem enigmas assustadores, as profecias são a forma como Deus, em Seu imenso amor, nos oferece um mapa para o presente e uma janela para o futuro. Elas nos mostram que, em meio aos acontecimentos históricos, há um plano divinamente orquestrado que culminará no evento mais glorioso de todos: a volta de Jesus Cristo.
            </p>
        </div>
    </section>

    <!-- Por Que Estudar -->
    <section class="why-study-section">
        <div class="why-study-content">
            <div class="section-header">
                <h2>Por Que Estudar as Profecias Hoje?</h2>
                <p>O estudo das profecias não é para nos causar medo, mas para fortalecer nossa fé e nos encher de esperança.</p>
            </div>

            <div class="benefits-grid">
                <!-- Item 1 -->
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Encontrar Paz em Meio ao Caos</h3>
                        <p>Compreender que Deus está no controle de cada detalhe da história.</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i data-lucide="eye"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Entender os Sinais do Nosso Tempo</h3>
                        <p>Reconhecer os acontecimentos atuais à luz dos escritos sagrados.</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i data-lucide="smile"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Preparar-se com Alegria</h3>
                        <p>Focar no que realmente importa enquanto aguardamos o retorno de nosso Salvador.</p>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i data-lucide="heart"></i>
                    </div>
                    <div class="benefit-text">
                        <h3>Conhecer Jesus Mais Profundamente</h3>
                        <p>Ver Cristo como o centro de toda a mensagem profética, do início ao fim.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Guias de Estudo -->
    <section class="guides-section">
        <div class="guides-content">
            <div class="guides-header">
                <h2>Seu Guia Para os Últimos Acontecimentos</h2>
                <p>
                    Para ajudar você nesta jornada fascinante de descoberta, preparamos dois guias de estudo incríveis, baseados em dois dos livros proféticos mais importantes da Bíblia: <strong>Daniel</strong> e <strong>Apocalipse</strong>.
                </p>
                <p class="highlight">Comece sua jornada hoje mesmo, escolhendo a melhor forma para você!</p>
            </div>

            <!-- Daniel Card -->
            <div class="guide-card guide-card-blue">
                <div class="guide-sidebar">
                    <i data-lucide="book-open"></i>
                    <h3>As Profecias de Daniel</h3>
                    <p>O Fundamento da História</p>
                </div>
                <div class="guide-content">
                    <p>
                        O livro de Daniel é a chave que abre nosso entendimento sobre o plano de Deus através dos séculos. Descubra como Deus revelou o surgimento e a queda de impérios mundiais com séculos de antecedência, estabelecendo um alicerce sólido para compreendermos as profecias do tempo do fim. Este estudo é essencial para quem deseja decifrar o Apocalipse.
                    </p>
                    <div class="guide-actions">
                        <div class="action-row">
                            <a href="{{ asset('downloads/profecias-daniel.pdf') }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                                <i data-lucide="download"></i>
                                Baixar em PDF
                            </a>
                            <span class="action-hint">Tenha acesso imediato ao guia completo.</span>
                        </div>
                        <div class="action-row">
                            <a href="https://cursos.novotempo.com/biblia-facil-daniel/correio/" target="_blank" class="btn-secondary">
                                <i data-lucide="mail"></i>
                                Receber pelos Correios
                            </a>
                            <span class="action-hint">Material impresso gratuito em sua casa.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apocalipse Card -->
            <div class="guide-card guide-card-purple">
                <div class="guide-sidebar">
                    <i data-lucide="sun"></i>
                    <h3>Apocalipse</h3>
                    <p>A Revelação da Vitória de Jesus</p>
                </div>
                <div class="guide-content">
                    <p>
                        Longe de ser um livro selado e misterioso, o Apocalipse é a "revelação de Jesus Cristo". Este guia de estudo irá conduzi-lo, capítulo por capítulo, a desvendar os símbolos e a encontrar a mensagem central do livro: a certeza da vitória final de Jesus sobre o mal e a promessa de um novo lar para todos os que O aceitam.
                    </p>
                    <div class="guide-actions">
                        <div class="action-row">
                            <a href="{{ asset('downloads/apocalipse.pdf') }}" target="_blank" rel="noopener noreferrer" class="btn-primary btn-purple">
                                <i data-lucide="download"></i>
                                Baixar em PDF
                            </a>
                            <span class="action-hint">Mergulhe agora mesmo no estudo digital.</span>
                        </div>
                        <div class="action-row">
                            <a href="https://cursos.novotempo.com/apocalipse/correio/" target="_blank" class="btn-secondary btn-purple-secondary">
                                <i data-lucide="mail"></i>
                                Receber pelos Correios
                            </a>
                            <span class="action-hint">Receba no conforto da sua casa.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Conclusão Call to Action -->
    <section class="cta-section">
        <!-- Elemento decorativo de fundo -->
        <div class="cta-bg">
             <div class="cta-bg-blob-1"></div>
             <div class="cta-bg-blob-2"></div>
        </div>

        <div class="cta-content">
            <h2>A Hora é Agora!</h2>
            <p>
                Não adie a oportunidade de encontrar as respostas que sua alma procura. O estudo da profecia nos aproxima de Jesus e nos dá a segurança de que pertencemos a um Reino que jamais será destruído.
            </p>
            <p class="emphasis">
                Estamos aqui para apoiar você em cada passo dessa caminhada de fé.
            </p>
            <div class="cta-quote">
                <p>
                    "Que a paz e a esperança de um futuro glorioso com Cristo inundem seu coração."
                </p>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush
