@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Início')

@section('meta-description', 'IASD Central de Brasília - Uma comunidade de fé, amor e esperança. Participe de nossos cultos aos sábados, estudos bíblicos, eventos e programações especiais.')
@section('og-title', 'IASD Central de Brasília - Uma comunidade de fé e esperança')
@section('og-description', 'Bem-vindo à IASD Central de Brasília! Junte-se a nós em uma jornada de fé, comunhão e transformação.')
@section('page-name', 'Início')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="slider">
    <div class="list">
        <div class="item">
            <img src="{{ asset('img/carrousel/1.webp') }}" alt="Slide 1 - IASD Central de Brasília" fetchpriority="high" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/2.webp') }}" alt="Slide 2" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/3.webp') }}" alt="Slide 3" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/4.webp') }}" alt="Slide 4" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/5.webp') }}" alt="Slide 5" decoding="async" width="1280" height="720">
        </div>
        <div class="item">
            <img src="{{ asset('img/carrousel/6.webp') }}" alt="Slide 6" decoding="async" width="1280" height="720">
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

<section class="boletim-preview-section">
    <div class="boletim-preview-container">
        <div class="boletim-preview-header">
            <span class="boletim-preview-eyebrow">Central Informa</span>
            <h2 class="acb-title-serif">Boletim Informativo</h2>
            <p>Fique por dentro das atividades da semana da IASD Central de Brasília.</p>
        </div>

        @php
            $boletimBase = 'img/boletim/boletim_08_08_2026';
            $oracao365Base = $boletimBase . '/365 Dias de Oração';

            $linkify = static function (?string $text): ?string {
                if ($text === null || $text === '') {
                    return $text;
                }
                return preg_replace(
                    '~(https?://[^\s<]+)~',
                    '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
                    $text
                );
            };

            $texto365Dias = 'Continuamos envolvidos no projeto Jornada de Oração: Frutos do Espírito. Ao longo deste mês, vamos orar pedindo a Deus que desenvolva em nossa vida o fruto: O desafio da SEGUNDA semana de AGOSTO é BONDADE: Ore para que Deus lhe mostre oportunidades reais de ajudar alguém durante a semana.';

            $boletins = [
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Culto Permanente.jpg',
                    'alt' => 'Culto Permanente',
                    'title' => 'Culto Permanente',
                    'text' => 'Participe do Culto Permanente coordenado pela Igreja Adventista Central de Brasília. Um momento especial de paz, oração e fortalecimento espiritual para todos. Todo 3º sábado de cada mês, às no Hospital Brasília Lago Sul, Espaço Energia.',
                ],
                [
                    'type' => 'image',
                    'src' => $oracao365Base . '/WhatsApp Image 2026-05-30 at 22.57.51 (2).jpeg',
                    'alt' => '365 Dias de Oração — Jornada de Oração',
                    'title' => '365 Dias de Oração',
                    'text' => $texto365Dias,
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Agasalho.jpg',
                    'alt' => 'Campanha do agasalho',
                    'title' => 'Campanha do Agasalho',
                    'text' => 'A ASA está arrecadando agasalhos, cobertores e roupas de frio em geral. Colabore doando itens limpos e em bom estado de conservação; o que não lhe serve mais será de grande valia para famílias que enfrentam o rigor deste inverno.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Bordado.jpg',
                    'alt' => 'Curso de bordado ASA',
                    'title' => 'Curso de Bordado',
                    'text' => 'Oportunidade Imperdível: Curso de Bordado ASA! Venha aprender técnicas exclusivas para confeccionar lindas peças. Aprender a bordar é uma excelente oportunidade para empreender e conquistar uma renda extra. Todos os domingos às 9h.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Voluntariado.jpeg',
                    'alt' => 'Voluntariado nos ministérios da igreja',
                    'title' => 'Voluntariado',
                    'text' => 'Seja voluntário em um de nossos ministérios! Acesse o link/QR Code e escolha o departamento da igreja que mais combina com você.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/PG FEm.jpeg',
                    'alt' => 'Pequeno Grupo Feminino',
                    'title' => 'PG Feminino',
                    'text' => 'Atenção mulheres! Temos um encontro especial a cada 15 dias no nosso PG Feminino, um espaço de acolhimento, amizade e fé.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Oi Amiga!.jpg',
                    'alt' => 'Oi Amiga — capacitação para estudos bíblicos',
                    'title' => 'Oi Amiga',
                    'text' => 'Se você tem o desejo de compartilhar a Palavra de Deus, mas nunca deu um estudo bíblico e não sabe por onde começar, este convite é para você.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Quebrando o Silêncio.jpg',
                    'alt' => 'Quebrando o Silêncio — Idosos em Risco',
                    'title' => 'Quebrando o Silêncio',
                    'text' => 'Idosos em Risco — A violência que atinge quem mais precisa de cuidado. Desde 2002, a Igreja Adventista do Sétimo Dia atua na prevenção contra o abuso e a violência por meio do projeto Quebrando o Silêncio.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Encontro de Mulheres com Darleide.jpg',
                    'alt' => 'Encontro especial de mulheres — Vida que Eleva',
                    'title' => 'Encontro Especial de Mulheres',
                    'text' => 'Queridas amigas, devido à necessidade de recuperação da nossa querida Darleide Alves, o encontro "Vida que Eleva" foi transferido para o dia 22 de agosto, no mesmo horário.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Quartas da Família.jpg',
                    'alt' => 'Quartas da Família — Chaves da Felicidade Familiar',
                    'title' => 'Quartas da Família',
                    'text' => 'Queridos irmãos, o Ministério da Família da Igreja Central de Brasília convida você e sua família para a série "Chaves da Felicidade Familiar", que acontecerá de 05 de agosto a 23 de setembro.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Oficina do Bem.jpg',
                    'alt' => 'Oficina do Bem — Doutores de Esperança',
                    'title' => 'Coração do Bem',
                    'text' => 'Participe da Oficina do Bem, às 9h, na sala dos Doutores de Esperança. Onde voluntários se reúnem para confeccionar corações de feltro.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Entrega de livros.jpg',
                    'alt' => 'Entrega de livros missionários',
                    'title' => 'Entrega de Livros',
                    'text' => 'O descanso acabou, mas a nossa missão só está começando! Agosto chegou e, com ele, renovamos nossas energias para o maior compromisso do nosso ano.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Classe de Saúde - Divulgação.jpg',
                    'alt' => 'Classe de Saúde — retorno em 15 de agosto',
                    'title' => 'Classe de Saúde',
                    'text' => 'No mês de julho, a Classe Vida e Saúde da Igreja Adventista Central de Brasília esteve de férias. Retornaremos às nossas atividades no dia 15 de agosto.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/SGI.jpg',
                    'alt' => 'SGI — Sistema de Gerenciamento de Interessados',
                    'title' => 'SGI',
                    'text' => 'Está no ar o SGI - Sistema de Gerenciamento de Interessados. Querido membro, se você está estudando a Bíblia com alguém, a Igreja Central conta agora com um sistema de cadastro de interessados.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Classe pós-batismo.jpg',
                    'alt' => 'Classe Pós-Batismo — Programa de Discipulado 2026',
                    'title' => 'Classe Pós-Batismo',
                    'text' => 'Queridos membros, aos sábados, às 10h45, na Sala do Ministério de Oração, acontece o Programa de Discipulado da Classe Pós-Batismal de 2026.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Francês.jpeg',
                    'alt' => 'Classe de Escola Sabatina em Francês',
                    'title' => 'Classe de Francês',
                    'text' => 'Temos uma excelente notícia para os amantes de idiomas e do estudo da Palavra: a Classe de Escola Sabatina em Francês está de volta!',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Quartas de Poder 2026.jpg',
                    'alt' => 'Quartas de Poder — O Mover do Espírito',
                    'title' => 'Quartas de Poder',
                    'text' => 'Convidamos toda a comunidade para os cultos especiais do projeto Quartas de Poder, que serão realizados nas últimas quartas-feiras de cada mês, sempre às 19h30.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Série Coisas Estranhas.png',
                    'alt' => 'Série Coisas Estranhas — Domingos Especiais',
                    'title' => 'Coisas Estranhas',
                    'text' => 'A morte é um grande mistério. Afinal, o que realmente acontece quando fechamos os olhos pela última vez? Venha participar da nova série dos domingos especiais.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/cientistas adventistas.jpg',
                    'alt' => 'VII Congresso Internacional de Cientistas Adventistas',
                    'title' => 'Congresso de Cientistas Adventistas',
                    'text' => 'Ciência, Fé e Redenção: interpretando o mundo à luz do grande conflito. A sétima edição do Congresso Internacional será realizada de 11 a 13 de setembro de 2026 em Cachoeira–BA.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Rádio NT.jpeg',
                    'alt' => 'Rádio Novo Tempo em Brasília — 92.9 FM',
                    'title' => 'Rádio Novo Tempo',
                    'text' => 'É com muita alegria que anunciamos a chegada da Rádio Novo Tempo à capital federal. O lançamento oficial será no dia 24 de julho, às 18h.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Sementes Musicais.jpg',
                    'alt' => 'Sementes Musicais — flautas doces no CEMAB',
                    'title' => 'Sementes Musicais',
                    'text' => 'O projeto de musicalização através das flautas doces denominado Sementes Musicais retomará suas atividades no próximo sábado, dia 15 de agosto.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/CEMAB.jpg',
                    'alt' => 'CEMAB — matrículas abertas',
                    'title' => 'CEMAB',
                    'text' => 'O Centro Musical Adventista de Brasília está com matrículas abertas. Queridos pais e responsáveis, as matrículas para o 2º módulo de 2026 do CEMAB já estão abertas!',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Orquestra CEMAB.jpg',
                    'alt' => 'Orquestra CEMAB — ensaios semanais',
                    'title' => 'Orquestra CEMAB',
                    'text' => 'A Orquestra CEMAB retomará suas atividades no próximo sábado, dia 15 de agosto, promovendo ensaios semanais.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/Desbravadores.jpg',
                    'alt' => 'Apoie um Desbravador — Campori DSA 2027',
                    'title' => 'Rumo ao Campori da DSA 2027',
                    'text' => 'O Clube de Desbravadores Cruzeiro do Sul já está se preparando para participar do Campori da Divisão Sul-Americana, o maior encontro de Desbravadores.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/ave_branca_run.jpeg',
                    'alt' => 'Corrida Ave Branca — 60 anos',
                    'title' => 'Corrida Ave Branca',
                    'text' => 'O Ministério Jovem da Igreja Adventista Central de Brasília está apoiando a corrida comemorativa de 60 anos do Clube de Desbravadores Ave Branca.',
                ],
                [
                    'type' => 'image',
                    'src' => $boletimBase . '/ZAP.jpg',
                    'alt' => 'Canal Central Informa no WhatsApp',
                    'title' => 'Canal WhatsApp',
                    'text' => 'Perdeu algum detalhe dos nossos anúncios? Não se preocupe. O Central Informa está disponível no nosso canal oficial no WhatsApp.',
                ],
            ];

            foreach ($boletins as &$boletim) {
                if (!empty($boletim['text'])) {
                    $boletim['text'] = $linkify($boletim['text']);
                }
            }
            unset($boletim);
        @endphp

        <div class="boletim-preview-grid" id="boletim-preview-grid">
            @foreach ($boletins as $index => $boletim)
                <article class="boletim-preview-item {{ $index >= 4 ? 'boletim-preview-item--hidden' : '' }}" style="--item-index: {{ $index }}">
                    <div class="boletim-preview-item__image">
                        <img src="{{ asset($boletim['src']) }}" alt="{{ $boletim['alt'] }}" loading="{{ $index < 4 ? 'eager' : 'lazy' }}" decoding="async" width="400" height="225">
                    </div>
                    <div class="boletim-preview-item__content">
                        <h3 class="boletim-preview-item__title">{{ $boletim['title'] }}</h3>
                        <p class="boletim-preview-item__text">{!! $boletim['text'] !!}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="boletim-preview-actions">
            <button type="button" class="boletim-preview-toggle" id="boletim-preview-toggle" aria-expanded="false">
                <span class="boletim-preview-toggle__label">Ver mais tópicos</span>
                <span class="boletim-preview-toggle__icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>

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

@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="{{ asset('js/slider.js') }}" defer></script>
<script src="{{ asset('js/canais.js') }}" defer></script>
<script src="{{ asset('js/videos_youtube.js') }}" defer></script>
@endpush

