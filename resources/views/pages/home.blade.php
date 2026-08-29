@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Início')

@section('meta-description', 'IASD Central de Brasília - Uma comunidade de fé, amor e esperança. Participe de nossos cultos aos sábados, estudos bíblicos, eventos e programações especiais.')
@section('og-title', 'IASD Central de Brasília - Uma comunidade de fé e esperança')
@section('og-description', 'Bem-vindo à IASD Central de Brasília! Junte-se a nós em uma jornada de fé, comunhão e transformação.')
@section('page-name', 'Início')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
@endpush

@section('content')
<div class="slider">
    <div class="list">
        <div class="item">
            <img src="{{ asset('img/carrousel/1.webp') }}" alt="Slide 1 - IASD Central de Brasília" fetchpriority="high" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/cemab.webp') }}" alt="Slide 2 - CEMAB, Centro Musical Adventista de Brasília: matricule-se, (61) 99612-5450" decoding="async" width="1920" height="700">
        </div>
        <div class="item">
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdOx1UFYwKkJhHYkPQzXiUHCMZBxTKQjanOfLQtXZc27uZi2Q/viewform" target="_blank" rel="noopener noreferrer" class="carousel-cta-link">
                <img src="{{ asset('img/carrousel/corais.webp') }}" alt="Slide 3 - Participe de um coral: infantil, juvenil, adolescente, jovem, adventista de Brasília, feminino, masculino e madrigal" decoding="async" width="1920" height="700">
                <span class="carousel-click-overlay" aria-hidden="true">
                    <span class="carousel-click-hint">
                        <i class="bi bi-hand-index"></i>
                    </span>
                </span>
            </a>
        </div>
        <div class="item">
            <a href="https://forms.gle/nmZztx1nZiij6i2E7" target="_blank" rel="noopener noreferrer" class="carousel-cta-link">
                <img src="{{ asset('img/carrousel/4.webp') }}" alt="Slide 4 - ASA" decoding="async" width="1280" height="720">
                <span class="carousel-click-overlay" aria-hidden="true">
                    <span class="carousel-click-hint">
                        <i class="bi bi-hand-index"></i>
                    </span>
                </span>
            </a>
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/5.webp') }}" alt="Slide 5" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/series-coisas-estranhas.webp') }}" alt="Slide 6 - Nova série dos domingos especiais: Coisas Estranhas, agosto e setembro, 19h" decoding="async" width="1920" height="700">
        </div>
    </div>

    <div class="buttons">
        <button id="prev"><img src="{{ asset('img/btn-left.webp') }}" alt="Anterior" width="40" height="40"></button>
        <button id="next"><img src="{{ asset('img/btn-right.webp') }}" alt="Próximo" width="40" height="40"></button>
    </div>

    <ul class="dots">
        <li class="active"></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

<a class="boletim-home" href="{{ route('boletim-digital') }}" aria-label="Acessar boletim digital">
    <div class="boletim-home__content">
        <div class="boletim-home__text">
            <span class="boletim-home__eyebrow">Central Informa</span>
            <h2 class="acb-title-serif">Boletim Informativo</h2>
            <p>Fique por dentro das atividades da semana da IASD Central de Brasília.</p>
        </div>
        <span class="boletim-home__button">Acessar boletim</span>
    </div>
</a>

<section class="noticias-section">
    <div class="noticias-container">
        <div class="noticias-header">
            <span class="noticias-eyebrow">Notícias</span>
            <h2 class="acb-title-serif">Últimas Notícias da Nossa Comunidade</h2>
        </div>

        <a href="{{ route('noticia-desbravadores') }}" class="noticia-card" aria-label="Ler notícia completa sobre o Clube de Desbravadores">
            <div class="noticia-card__image">
                <img src="{{ asset('img/noticias/desbravadores-1.jpeg') }}" alt="Clube de Desbravadores Cruzeiro do Sul em Campori APLaC 2026" loading="lazy" decoding="async" width="600" height="338">
            </div>
            <div class="noticia-card__content">
                <div class="noticia-card__meta">
                    <span class="noticia-card__categoria">Religião &amp; Comunidade</span>
                    <span class="noticia-card__data">10/06/2026</span>
                </div>
                <h3 class="noticia-card__title">Clube de Desbravadores Cruzeiro do Sul celebra participação marcante em Campori APLaC 2026</h3>
                <p class="noticia-card__excerpt">Evento de quatro dias reuniu jovens para atividades de desenvolvimento pessoal, espiritual e fortalecimento comunitário; foco agora se volta para a edição sul-americana de 2027.</p>
                <span class="noticia-card__cta">Ler notícia completa</span>
            </div>
        </a>
    </div>
</section>

<span class="span_cards">
    <div class="container_cards">
        <a class="card" href="{{ route('estudo-biblico') }}">
            <img src="{{ asset('img/cards/estudo_biblico.webp') }}" alt="Estudo Bíblico" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Estudo Bíblico:<br>Uma Jornada para Conectar-se com Deus</h2>
            <p>Procurando respostas, fortalecimento espiritual ou alívio para desafios emocionais? O Estudo Bíblico é o caminho!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('escola-sabatina') }}">
            <img src="{{ asset('img/cards/escola_sabatina.webp') }}" alt="Escola Sabatina" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Venha Crescer Conosco na Escola Sabatina!</h2>
            <p>A Escola Sabatina é um presente de Deus para você! Não é apenas um momento de estudo, mas um encontro semanal que alimenta a alma, fortalece a fé e nos une como família em Cristo.</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('oracao-visita') }}">
            <img src="{{ asset('img/cards/oracao.webp') }}" alt="Oração e Visita" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Precisa de Oração ou Visita? Vamos Interceder por Você!</h2>
            <p>Não carregue suas lutas sozinho(a). Deus ouve cada oração e, através da nossa comunidade, queremos ser um canal de esperança para sua vida.</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('programacoes') }}">
            <img src="{{ asset('img/cards/eventos.webp') }}" alt="Programações e Eventos" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Programações </h2>
            <p>Nossa comunidade está em constante movimento! Todos os meses, os ministérios organizam programações especiais que abraçam todas as idades. Venha participar e fortalecer sua fé junto à família da igreja. Aqui, há espaço para todos!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('asa') }}">
            <img src="{{ asset('img/cards/asa.webp') }}" alt="Ação Solidária Adventista" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Ação Solidária Adventista (ASA) </h2>
            <p>A ASA é o braço social da Igreja Adventista, dedicado a servir e transformar vidas através de ações de amor e solidariedade. Seja parte desta corrente do bem!</p>
            <span class="card_cta">Saiba mais</span>
        </a>

        <a class="card" href="{{ route('secretaria') }}">
            <img src="{{ asset('img/cards/secretaria.webp') }}" alt="Secretaria da Igreja" loading="lazy" decoding="async" width="400" height="300">
            <h2 class="acb-title-serif">Fale com a secretaria </h2>
            <p>Na Igreja Adventista do Sétimo Dia, cada membro é parte essencial da família de Deus. Para cuidar bem uns dos outros e garantir que nossa missão avance com eficiência, é fundamental que seus dados estejam sempre atualizados.</p>
            <span class="card_cta">Saiba mais</span>
        </a>
    </div>
</span>

<div class="canais">
    <div class="btn_canais">
        <button id="btn1">Youtube</button>
        <button id="btn2">Instagram</button>
    </div>
    <div id="div1" class="youtube">
        <h2 class="acb-title-serif">Acompanhe nossas transmissões</h2>
        <div class="yt_stage is-loading" data-yt-stage>
            <div class="yt_loader" data-yt-loader role="status" aria-live="polite" aria-label="Carregando vídeos do YouTube">
                <span class="yt_loader_spinner" aria-hidden="true"></span>
            </div>
            <div class="content_yt" data-yt-content aria-hidden="true">
                <div class="yt_ao_vivo">
                    <button type="button" class="yt_lazy" data-yt-lazy aria-label="Reproduzir vídeo do YouTube">
                        <img class="yt_lazy_thumb" src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="" loading="lazy" decoding="async">
                        <span class="yt_lazy_play" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="yt_em_breve">
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                    <div class="thumb_em_breve">
                        <a href="" class="thumb_em_breve_link" aria-label="Abrir vídeo no YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/canais/yt_em_breve.avif') }}" alt="Em breve" loading="lazy" decoding="async">
                        </a>
                        <a href="" class="thumb_em_breve_title" target="_blank" rel="noopener noreferrer">Título sermão</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="div2" class="content_insta">
        <div class="insta">
            <h2 class="acb-title-serif">instagram</h2>
        </div>
    </div>
</div>

<div class="cultos-section">
    <div class="cultos-container">
        <h2 class="acb-title-serif">Horários de Cultos</h2>
        <p>Você é muito bem-vindo(a) em todos os nossos encontros!</p>

        <div class="cultos-grid">
            <div class="culto-item culto-sabado">
                <div class="culto-icon">
                    <i class="bi bi-calendar3-event"></i>
                </div>
                <h3>Sábado</h3>
                <p>08h45 - Culto</p>
                <p>11h00 - Escola Sabatina</p>
                <p>17h30 - Culto jovem</p>
            </div>

            <div class="culto-item culto-domingo">
                <div class="culto-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3>Domingo</h3>
                <p>19h00 - Culto evangelístico</p>
            </div>

            <div class="culto-item culto-quarta">
                <div class="culto-icon">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <h3>Quarta-feira</h3>
                <p>19h30 - Culto de oração</p>
            </div>
        </div>
    </div>
</div>

<!-- Seção Liderança -->
<div class="lideranca-section">
    <h2 class="acb-title-serif" style="text-align: center; font-size: 2.5em; color: #003366; margin-bottom: 40px; font-weight: 500;">Liderança</h2>

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

@endsection

@push('scripts')
<script src="{{ asset('js/slider.js') }}?v={{ filemtime(public_path('js/slider.js')) }}" defer></script>
<script src="{{ asset('js/canais.js') }}" defer></script>
<script src="{{ asset('js/videos_youtube.js') }}" defer></script>
@endpush

