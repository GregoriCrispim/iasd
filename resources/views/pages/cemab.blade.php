@extends('layouts.app')

@section('title', 'IASD Central de Brasília - CEMAB (Centro Musical Adventista de Brasília)')

@section('meta-description', 'Conheça o CEMAB - Centro Musical Adventista de Brasília da IASD Central. Aulas de música, corais, orquestras e louvor a Deus.')
@section('og-title', 'CEMAB - Centro Musical Adventista')
@section('og-description', 'Música para glorificar a Deus! Participe de nossos corais e orquestras.')
@section('page-name', 'CEMAB')

@push('styles')
<style>

    .cemab-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .cemab-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .cemab-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .cemab-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: justify;
        max-width: 900px;
        margin: 0 auto;
    }

    .oquee-section {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 50px 40px;
        border-radius: 18px;
        margin: 30px 0;
        text-align: center;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .oquee-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #0b2a4a;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .oquee-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.8;
        color: rgba(15, 23, 42, 0.85);
        margin-bottom: 0;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .cursos-section {
        margin: 60px 0;
    }

    .cursos-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .cursos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .curso-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .curso-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .curso-card .emoji {
        font-size: 3.5em;
        margin-bottom: 15px;
        display: block;
    }

    .curso-card h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.3em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .cursos-info {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        text-align: center;
    }

    .cursos-info p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2rem;
        color: #333;
        font-weight: 500;
    }

    .porque-section {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 60px 40px;
        border-radius: 18px;
        margin: 50px 0;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .porque-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #0b2a4a;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .motivos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .motivo-card {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.10);
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 12px 22px rgba(15, 23, 42, 0.08);
    }

    .motivo-card .icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        display: block;
        color: #0b2a4a;
    }

    .motivo-card p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.6;
        color: rgba(15, 23, 42, 0.78);
    }

    .eventos-section {
        margin: 60px 0;
    }

    .eventos-section h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.5em;
        color: #003366;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .eventos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .evento-card {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .evento-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }

    .evento-card .emoji {
        font-size: 3em;
        margin-bottom: 15px;
        display: block;
    }

    .evento-card h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2em;
        color: #003366;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .instagram-section {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 50px 40px;
        border-radius: 18px;
        margin: 50px 0;
        text-align: center;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .instagram-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #0b2a4a;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .instagram-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: rgba(15, 23, 42, 0.78);
        margin-bottom: 25px;
    }

    .btn-instagram {
        display: inline-block;
        background-color: #833ab4;
        color: #fff;
        border: 1px solid rgba(131, 58, 180, 0.25);
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-instagram:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.18);
        background-color: #6f2ea0;
    }

    .contato-section {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        margin: 50px 0;
        text-align: center;
    }

    /* Sem espaço entre "Venha nos Conhecer!" e o versículo */
    .cemab-container > .contato-section {
        margin-bottom: 0 !important;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .cemab-container > .versiculo-section {
        margin-top: 0 !important;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .contato-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #003366;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .contato-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    .contato-item {
        text-align: center;
    }

    .contato-item .icon {
        font-size: 2em;
        margin-bottom: 10px;
        display: block;
    }

    .contato-item h4 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1em;
        color: #003366;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .contato-item p {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        color: #333;
        margin-bottom: 5px;
    }

    .contato-item a {
        color: #003366;
        text-decoration: none;
        font-weight: 600;
    }

    .contato-item a:hover {
        text-decoration: underline;
    }

    .versiculo-section {
        background: #0b2a4a;
        padding: 50px 40px;
        border-radius: 18px;
        margin: 50px 0;
        text-align: center;
        color: #fff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .versiculo-section blockquote {
        font-family: 'Roboto', sans-serif;
        font-size: 1.5rem;
        font-style: italic;
        color: #f8f9fa;
        margin-bottom: 20px;
        line-height: 1.8;
    }

    .versiculo-section cite {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2rem;
        color: #f1c9a1;
        font-weight: 600;
    }

    .site-section {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-top: 4px solid rgba(11, 42, 74, 0.22);
        padding: 50px 40px;
        border-radius: 18px;
        margin: 50px 0;
        text-align: center;
        color: #0f172a;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .site-section h3 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2em;
        color: #0b2a4a;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .site-section p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        color: rgba(15, 23, 42, 0.78);
        margin-bottom: 25px;
    }

    .btn-site {
        display: inline-block;
        background-color: #0b2a4a;
        color: #fff;
        border: 1px solid rgba(11, 42, 74, 0.20);
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        transition: transform 0.3s, box-shadow 0.3s;
        font-family: 'Roboto', sans-serif;
    }

    .btn-site:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.18);
        background-color: #083055;
    }

    @media (max-width: 768px) {
        .cemab-container {
            padding: 20px 15px;
        }

        .cemab-intro {
            padding: 30px 20px;
        }

        .cemab-intro h1 {
            font-size: 2.2em;
        }

        .oquee-section,
        .porque-section,
        .versiculo-section,
        .instagram-section,
        .site-section {
            padding: 40px 20px;
        }

        .oquee-section h2,
        .porque-section h2 {
            font-size: 2em;
        }

        .cursos-grid,
        .eventos-grid,
        .motivos-grid {
            grid-template-columns: 1fr;
        }

        .contato-info {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/cemab/cemab_header.webp') }}" alt="CEMAB (Centro Musical Adventista de Brasília)" fetchpriority="high" decoding="async">

<div class="cemab-container">

    <!-- Seção Introdutória -->
    <div class="cemab-intro acb-fullbleed">
        <h1>CEMAB (Centro Musical Adventista de Brasília)</h1>
        <p>
            O CEMAB é um espaço dedicado ao ensino musical de excelência, integrado aos valores cristãos da Igreja Adventista. Aqui, crianças, jovens e adultos aprendem a expressar sua fé e talentos através de instrumentos, canto e teoria musical, em um ambiente acolhedor e inspirador!
        </p>
        <p>
            Transformando vidas através da música e da fé! No CEMAB, acreditamos que a música é mais que uma arte: é um propósito para servir e adorar a Deus. Com professores qualificados e infraestrutura moderna, oferecemos cursos de canto, piano, violão, cordas e teoria musical para todas as idades e níveis.
        </p>
    </div>

    <!-- Seção Destaque -->
    <div class="oquee-section acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-star-fill"></i> Transformando Vidas Através da Música <i class="bi bi-music-note-beamed"></i></h2>
        <p>
            Venha fazer parte do CEMAB e desenvolver seus talentos musicais em um ambiente cristão e acolhedor!
        </p>
    </div>

    <!-- Seção Cursos Oferecidos -->
    <div class="cursos-section">
        <h2 class="acb-title-serif"><i class="bi bi-journals"></i> Cursos Oferecidos</h2>

        <div class="cursos-grid">
            <div class="curso-card">
                <i class="bi bi-mic emoji"></i>
                <h3>Canto Popular e Erudito</h3>
            </div>

            <div class="curso-card">
                <i class="bi bi-music-note emoji"></i>
                <h3>Piano e Teclado</h3>
            </div>

            <div class="curso-card">
                <i class="bi bi-music-note-beamed emoji"></i>
                <h3>Violão/Guitarra</h3>
            </div>

            <div class="curso-card">
                <i class="bi bi-music-note-list emoji"></i>
                <h3>Cordas (Violino, Violoncelo)</h3>
            </div>

            <div class="curso-card">
                <i class="bi bi-music-note emoji"></i>
                <h3>Teoria Musical & Solfejo</h3>
            </div>
        </div>

        <div class="cursos-info">
            <p>Turmas personalizadas para todas as idades e níveis!</p>
        </div>
    </div>

    <!-- Seção Por Que Escolher o CEMAB -->
    <div class="porque-section acb-fullbleed">
        <h2 class="acb-title-serif"><i class="bi bi-award-fill"></i> Por Que Escolher o CEMAB?</h2>

        <div class="motivos-grid">
            <div class="motivo-card">
                <i class="bi bi-mortarboard-fill icon"></i>
                <p>Professores qualificados e comprometidos com o desenvolvimento integral dos alunos.</p>
            </div>

            <div class="motivo-card">
                <i class="bi bi-tools icon"></i>
                <p>Infraestrutura moderna com instrumentos de alta qualidade.</p>
            </div>

            <div class="motivo-card">
                <i class="bi bi-calendar-event-fill icon"></i>
                <p>Eventos musicais que conectam a comunidade e glorificam a Deus.</p>
            </div>

            <div class="motivo-card">
                <i class="bi bi-cash-coin icon"></i>
                <p>Preços acessíveis</p>
            </div>
        </div>
    </div>

    <!-- Seção Eventos e Apresentações -->
    <div class="eventos-section">
        <h2 class="acb-title-serif"><i class="bi bi-calendar3"></i> Eventos e Apresentações</h2>
        <p style="text-align: center; font-family: 'Roboto', sans-serif; font-size: 1.1rem; color: #666; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto;">
            No CEMAB, a música ganha vida em:
        </p>

        <div class="eventos-grid">
            <div class="evento-card">
                <i class="bi bi-balloon emoji"></i>
                <h4>Recitais ao final de cada semestre</h4>
            </div>

            <div class="evento-card">
                <i class="bi bi-calendar-date emoji"></i>
                <h4>Workshops com artistas convidados</h4>
            </div>

            <div class="evento-card">
                <i class="bi bi-feather emoji"></i>
                <h4>Participação em programas da igreja</h4>
            </div>
        </div>
    </div>

    <!-- Seção Instagram -->
    <div class="instagram-section acb-fullbleed">
        <h3 class="acb-title-serif">Siga nosso Instagram @cemab.escolademusica</h3>
        <p>Para ver as datas dos eventos e ficar por dentro das novidades!</p>
        <a href="https://instagram.com/cemab.escolademusica" target="_blank" class="btn-instagram">Seguir no Instagram</a>
    </div>

    <!-- Seção de Contato -->
    <div class="contato-section acb-fullbleed">
        <h3 class="acb-title-serif"><i class="bi bi-geo-alt-fill"></i> Venha nos Conhecer!</h3>

        <div class="contato-info">
            <div class="contato-item">
                <i class="bi bi-telephone icon"></i>
                <h4>Telefone</h4>
                <p><a href="tel:+5561996529846">(61) 99652-9846</a></p>
            </div>

            <div class="contato-item">
                <i class="bi bi-envelope icon"></i>
                <h4>E-mail</h4>
                <p><a href="mailto:contato@cemab.com.br">contato@cemab.com.br</a></p>
            </div>

            <div class="contato-item">
                <i class="bi bi-clock icon"></i>
                <h4>Horário de Atendimento</h4>
                <p>Segunda a Sexta</p>
                <p>8h às 12h e 14h às 17h</p>
            </div>
        </div>
    </div>

    <!-- Seção Versículo -->
    <div class="versiculo-section acb-fullbleed">
        <blockquote class="acb-quote acb-quote--glass" style="max-width: 900px; margin: 0 auto;">
            <p>"Louvem a Deus com harpa e som de cânticos!"</p>
            <span class="acb-quote__ref"><i class="bi bi-music-note"></i> Salmos 98:5 <i class="bi bi-music-note"></i></span>
        </blockquote>
        <p style="margin-top: 30px; font-size: 1.1rem;">
            No CEMAB, a música é mais que uma arte: é um propósito para servir e adorar!
        </p>
    </div>

    <!-- Seção Site -->
    <div class="site-section acb-fullbleed">
        <h3 class="acb-title-serif"><i class="bi bi-laptop"></i> Acompanhe nossas novidades</h3>
        <p>Acesse nossa página para saber mais sobre os cursos, eventos e inscrições!</p>
        <a href="https://cemab.vercel.app/" target="_blank" class="btn-site">Acessar Site do CEMAB</a>
    </div>

</div>
@endsection
