@extends('layouts.app')

@section('title', 'IASD Central de Brasília - EAD Adventista (UNASP)')

@section('meta-description', 'Estude a distância com a EAD Adventista (UNASP) com apoio da IASD Central de Brasília. Graduação e pós-graduação — Black November com condições especiais.')
@section('og-title', 'EAD Adventista UNASP — Central de Brasília')
@section('og-description', 'Graduação e pós-graduação com tradição adventista. Fale conosco e descubra o curso ideal.')
@section('page-name', 'EAD Adventista')
@section('body-class', 'page-ead')

@php
    $waPhone = '5561999352191';
    $waPhoneDisplay = '(61) 99935-2191';
    $waMessage = 'Olá! Quero saber mais sobre os cursos do EAD. Meu nome é:';
    $waEad = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMessage);
    $inscricaoUrl = 'https://lp.unasp.edu.br/vestibular-ead-ingresso/';

    $cursosGraduacao = [
        ['nome' => 'Administração', 'duracao' => '4 anos · 16 módulos', 'tipo' => 'Bacharelado', 'img' => 'adm.webp'],
        ['nome' => 'Análise e Desenvolvimento de Sistemas', 'duracao' => '2 anos e 6 meses · 10 módulos', 'tipo' => 'Tecnólogo', 'img' => 'ads.webp'],
        ['nome' => 'Banco de Dados', 'duracao' => '2 anos e 6 meses · 10 módulos', 'tipo' => 'Tecnólogo', 'img' => 'banco-de-dados.webp'],
        ['nome' => 'Ciências Contábeis', 'duracao' => '4 anos · 16 módulos', 'tipo' => 'Bacharelado', 'img' => 'ciencias-contabeis.webp'],
        ['nome' => 'Gestão Financeira', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'gestao-financeira.webp'],
        ['nome' => 'Engenharia de Software', 'duracao' => '4 anos · 16 módulos', 'tipo' => 'Bacharelado', 'img' => 'engenharia-de-software.webp'],
        ['nome' => 'Gestão Comercial', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'gestao-comercial.webp'],
        ['nome' => 'Gestão da Tecnologia da Informação', 'duracao' => '2 anos e 6 meses · 10 módulos', 'tipo' => 'Tecnólogo', 'img' => 'gestao-da-tecnologia-da-informacao.webp'],
        ['nome' => 'Gestão de Recursos Humanos', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'gestao-de-rh.webp'],
        ['nome' => 'Gestão Pública', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'gestao-publica.webp'],
        ['nome' => 'Jornalismo', 'duracao' => '4 anos · 16 módulos', 'tipo' => 'Bacharelado', 'img' => 'jornalismo.webp'],
        ['nome' => 'Logística', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'logistica.webp'],
        ['nome' => 'Marketing', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'marketing.webp'],
        ['nome' => 'Mídias Sociais e Digitais', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'midias-sociais-e-digitais.webp'],
        ['nome' => 'Pedagogia', 'duracao' => '4 anos · 8 semestres', 'tipo' => 'Licenciatura', 'img' => 'pedagogia.webp'],
        ['nome' => 'Processos Gerenciais', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'processos-gerenciais.webp'],
        ['nome' => 'Secretariado', 'duracao' => '2 anos · 8 módulos', 'tipo' => 'Tecnólogo', 'img' => 'secretariado.webp'],
        ['nome' => 'Segurança Cibernética', 'duracao' => '2 anos e 6 meses · 10 módulos', 'tipo' => 'Tecnólogo', 'img' => 'seguranca-cibernetica.webp'],
        ['nome' => 'Segurança da Informação', 'duracao' => '2 anos e 6 meses · 10 módulos', 'tipo' => 'Tecnólogo', 'img' => 'seguranca-da-informacao.webp'],
        ['nome' => 'Serviço Social', 'duracao' => '4 anos · 8 semestres', 'tipo' => 'Bacharelado', 'img' => 'servico-social.webp'],
    ];

    $cursosPosPath = resource_path('data/ead-pos-cursos.json');
    $cursosPos = file_exists($cursosPosPath)
        ? json_decode(file_get_contents($cursosPosPath), true, 512, JSON_THROW_ON_ERROR)
        : [];

    $cursos = array_merge($cursosGraduacao, $cursosPos);

    $formasIngresso = [
        ['titulo' => 'Prova online', 'texto' => 'Inscreva-se e faça uma redação sobre o tema proposto.', 'url' => 'https://ead.unasp.br/prova'],
        ['titulo' => 'Enem', 'texto' => 'Use a nota da redação do Enem no lugar da prova online.', 'url' => 'https://ead.unasp.br/enem'],
        ['titulo' => '2ª graduação', 'texto' => 'Envie diploma ou declaração de conclusão do curso anterior.', 'url' => 'https://ead.unasp.br/diplomados'],
        ['titulo' => 'Transferência', 'texto' => 'Envie o histórico acadêmico da graduação em andamento.', 'url' => 'https://ead.unasp.br/transferencia'],
        ['titulo' => 'Reingresso', 'texto' => 'Ex-aluno: selecione o curso e realize a prova online.', 'url' => 'https://ead.unasp.br/reingresso'],
    ];
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ead.css') }}?v={{ filemtime(public_path('css/ead.css')) }}">
@endpush

@section('content')
<div class="ead-page">
    {{-- Campanha: criativo Black November --}}
    <a class="ead-campaign" href="{{ $waEad }}" target="_blank" rel="noopener noreferrer" aria-label="Black November EAD — falar no WhatsApp">
        <img
            class="page-header-img"
            src="{{ asset('img/carrousel/black-november-ead.webp') }}?v={{ filemtime(public_path('img/carrousel/black-november-ead.webp')) }}"
            alt="Black November EAD Adventista: primeira mensalidade R$ 0,00, matricule-se já"
            width="1920"
            height="700"
            fetchpriority="high"
            decoding="async"
        >
    </a>

    {{-- Intro própria da Central --}}
    <section class="ead-intro acb-fullbleed">
        <div class="ead-shell">
            <p class="ead-eyebrow">IASD Central de Brasília · UNASP</p>
            <h1>EAD Adventista</h1>
            <p class="ead-intro__lead">
                Estude onde estiver, com a tradição da Educação Adventista e o acompanhamento da nossa igreja.
                Orientamos você na escolha do curso, polo e inscrição.
            </p>
            <div class="ead-intro__actions">
                <a class="ead-btn ead-btn--primary" href="#cursos">Ver cursos</a>
                <a class="ead-btn ead-btn--ghost" href="{{ $waEad }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i> Falar no WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- Cursos --}}
    <section class="ead-block" id="cursos">
        <div class="ead-shell">
            <div class="ead-block__head">
                <h2>Cursos</h2>
                <p>Busque, filtre e escolha entre graduação e pós-graduação — com apoio da Central.</p>
            </div>

            <div class="ead-catalog" data-ead-catalog data-per-page="6">
                <div class="ead-catalog__toolbar">
                    <label class="ead-catalog__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input
                            type="search"
                            data-ead-search
                            placeholder="Buscar curso…"
                            autocomplete="off"
                            aria-label="Buscar curso"
                        >
                    </label>
                    <div class="ead-catalog__filters" role="group" aria-label="Filtrar por tipo">
                        <button type="button" class="is-active" data-ead-filter="all">Todos</button>
                        <button type="button" data-ead-filter="Bacharelado">Bacharelado</button>
                        <button type="button" data-ead-filter="Tecnólogo">Tecnólogo</button>
                        <button type="button" data-ead-filter="Licenciatura">Licenciatura</button>
                        <button type="button" data-ead-filter="Pós-graduação">Pós-graduação</button>
                    </div>
                </div>

                <div class="ead-catalog__grid" data-ead-grid>
                    @foreach ($cursos as $i => $curso)
                        @php $imgUrl = asset('img/ead/cursos/' . $curso['img']); @endphp
                        <a
                            class="ead-course-card"
                            href="{{ $waEad }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-ead-card
                            data-nome="{{ Str::lower($curso['nome']) }}"
                            data-tipo="{{ $curso['tipo'] }}"
                            aria-label="Quero o curso {{ $curso['nome'] }} — falar no WhatsApp"
                            @if ($i >= 6) hidden @endif
                        >
                            <div class="ead-course-card__media">
                                <img
                                    @if ($i < 6)
                                        src="{{ $imgUrl }}"
                                        loading="eager"
                                    @else
                                        src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                        data-src="{{ $imgUrl }}"
                                        loading="lazy"
                                    @endif
                                    alt=""
                                    width="640"
                                    height="360"
                                    decoding="async"
                                >
                            </div>
                            <div class="ead-course-card__body">
                                <span class="ead-course-card__tipo">{{ $curso['tipo'] }}</span>
                                <h3>{{ $curso['nome'] }}</h3>
                                <p>{{ $curso['duracao'] }}</p>
                                <span class="ead-course-card__cta">
                                    Quero este curso
                                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <p class="ead-catalog__empty" data-ead-empty hidden>Nenhum curso encontrado para essa busca.</p>

                <div class="ead-catalog__pager" data-ead-pager>
                    <span class="ead-catalog__count" data-ead-count></span>
                    <nav class="ead-pagination" data-ead-pagination aria-label="Páginas de cursos"></nav>
                </div>
            </div>
        </div>
    </section>

    {{-- Por que estudar --}}
    <section class="ead-why acb-fullbleed">
        <div class="ead-why__media" aria-hidden="true">
            <img
                src="{{ asset('img/ead/formacao-aluno.webp') }}?v={{ filemtime(public_path('img/ead/formacao-aluno.webp')) }}"
                alt=""
                width="750"
                height="1000"
                loading="lazy"
                decoding="async"
            >
        </div>
        <div class="ead-why__content">
            <p class="ead-why__eyebrow">Educação Adventista a Distância</p>
            <h2>Formação adventista,<br><span class="ead-why__title-keep">no seu ritmo</span></h2>
            <p class="ead-why__lead">
                Qualidade acadêmica, valores cristãos e flexibilidade para estudar onde você estiver.
                Na Central, caminhamos com você desde a escolha do curso até a inscrição.
            </p>
            <div class="ead-why__points">
                <div class="ead-why__point">
                    <span class="ead-why__point-n" aria-hidden="true">01</span>
                    <p><strong>Excelência acadêmica</strong> Professores e material próprios da Educação Adventista.</p>
                </div>
                <div class="ead-why__point">
                    <span class="ead-why__point-n" aria-hidden="true">02</span>
                    <p><strong>Polos no Brasil</strong> Provas e suporte presenciais perto de você.</p>
                </div>
                <div class="ead-why__point">
                    <span class="ead-why__point-n" aria-hidden="true">03</span>
                    <p><strong>Acolhimento local</strong> Orientação da Central pelo WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Ingresso — CTAs --}}
    <section class="ead-block ead-block--soft" id="ingresso">
        <div class="ead-shell">
            <div class="ead-block__head">
                <h2>Como ingressar</h2>
                <p>Escolha a forma que combina com o seu histórico acadêmico e avance agora.</p>
            </div>
            <div class="ead-ingress-grid">
                @foreach ($formasIngresso as $forma)
                    <a
                        class="ead-ingress-cta"
                        href="{{ $forma['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span class="ead-ingress-cta__content">
                            <strong>{{ $forma['titulo'] }}</strong>
                            <span>{{ $forma['texto'] }}</span>
                        </span>
                        <span class="ead-ingress-cta__action">
                            Começar
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                @endforeach
            </div>
            <div class="ead-editais">
                <a class="ead-btn ead-btn--primary" href="https://cdn.unasp.edu.br/all/editais/graduacao/2027/edital-ead-2027-final.pdf" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i> Edital 2027
                </a>
                <a class="ead-btn ead-btn--ghost-dark" href="https://cdn.unasp.edu.br/all/editais/graduacao/2027/edital-de-classificacao-2026.2.pdf" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-file-earmark-check" aria-hidden="true"></i> Classificação 2026.2
                </a>
            </div>
        </div>
    </section>

    {{-- Atendimento local --}}
    <section class="ead-local acb-fullbleed" id="atendimento">
        <div class="ead-shell ead-local__inner">
            <div>
                <h2>Atendimento na Central</h2>
                <p>
                    Tire dúvidas sobre cursos, polos e inscrição com a nossa equipe.
                    O mesmo número do criativo Black November.
                </p>
            </div>
            <div class="ead-local__cta">
                <a class="ead-btn ead-btn--whatsapp" href="{{ $waEad }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    {{ $waPhoneDisplay }}
                </a>
                <a class="ead-btn ead-btn--ghost" href="{{ $inscricaoUrl }}" target="_blank" rel="noopener noreferrer">Ir para o vestibular</a>
            </div>
        </div>
    </section>
</div>

<a
    class="ead-whatsapp-float"
    href="{{ $waEad }}"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Falar no WhatsApp sobre os cursos do EAD"
>
    <span class="ead-whatsapp-float__badge" aria-hidden="true">1</span>
    <span class="ead-whatsapp-float__icon">
        <img
            src="{{ asset('img/ead/whatsapp-logo.png') }}"
            alt=""
            width="33"
            height="33"
            decoding="async"
        >
    </span>
</a>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.querySelector('[data-ead-catalog]');
    if (!root) return;

    var search = root.querySelector('[data-ead-search]');
    var grid = root.querySelector('[data-ead-grid]');
    var cards = Array.prototype.slice.call(root.querySelectorAll('[data-ead-card]'));
    var empty = root.querySelector('[data-ead-empty]');
    var countEl = root.querySelector('[data-ead-count]');
    var pagination = root.querySelector('[data-ead-pagination]');
    var filterBtns = Array.prototype.slice.call(root.querySelectorAll('[data-ead-filter]'));
    var perPage = parseInt(root.getAttribute('data-per-page') || '6', 10);
    var page = 1;
    var activeFilter = 'all';

    function normalize(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function loadCardImage(card) {
        var img = card.querySelector('img[data-src]');
        if (!img) return;
        img.src = img.getAttribute('data-src');
        img.removeAttribute('data-src');
    }

    function matchedCards() {
        var query = normalize(search && search.value);
        return cards.filter(function (card) {
            var nome = normalize(card.getAttribute('data-nome'));
            var tipo = card.getAttribute('data-tipo') || '';
            var matchesFilter = activeFilter === 'all' || tipo === activeFilter;
            var matchesQuery = !query || nome.indexOf(query) !== -1;
            return matchesFilter && matchesQuery;
        });
    }

    function chevron(dir) {
        var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        svg.setAttribute('aria-hidden', 'true');
        var poly = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
        poly.setAttribute('points', dir === 'prev' ? '15 18 9 12 15 6' : '9 18 15 12 9 6');
        svg.appendChild(poly);
        return svg;
    }

    function renderPagination(totalPages) {
        if (!pagination) return;
        pagination.innerHTML = '';
        if (totalPages <= 1) {
            pagination.hidden = true;
            return;
        }
        pagination.hidden = false;

        function addBtn(content, targetPage, opts) {
            opts = opts || {};
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ead-pagination__btn' + (opts.current ? ' is-active' : '');
            if (typeof content === 'string') {
                btn.textContent = content;
            } else {
                btn.appendChild(content);
            }
            if (opts.disabled) btn.disabled = true;
            if (opts.aria) btn.setAttribute('aria-label', opts.aria);
            if (opts.current) btn.setAttribute('aria-current', 'page');
            if (!opts.disabled && typeof targetPage === 'number') {
                btn.addEventListener('click', function () {
                    page = targetPage;
                    update({ scroll: true });
                });
            }
            pagination.appendChild(btn);
        }

        addBtn(chevron('prev'), Math.max(1, page - 1), {
            disabled: page <= 1,
            aria: 'Página anterior'
        });

        // Mesmo critério do UNASP: 1, última e vizinhas (±1) da página atual
        var pages = [];
        for (var i = 1; i <= totalPages; i += 1) {
            if (i === 1 || i === totalPages || Math.abs(i - page) <= 1) {
                pages.push(i);
            }
        }

        pages.forEach(function (num, idx) {
            if (idx > 0 && num - pages[idx - 1] > 1) {
                var ellipsis = document.createElement('span');
                ellipsis.className = 'ead-pagination__ellipsis';
                ellipsis.textContent = '…';
                ellipsis.setAttribute('aria-hidden', 'true');
                pagination.appendChild(ellipsis);
            }
            addBtn(String(num), num, {
                current: num === page,
                aria: 'Página ' + num
            });
        });

        addBtn(chevron('next'), Math.min(totalPages, page + 1), {
            disabled: page >= totalPages,
            aria: 'Próxima página'
        });
    }

    function update(opts) {
        opts = opts || {};
        var matches = matchedCards();
        var total = matches.length;
        var totalPages = Math.max(1, Math.ceil(total / perPage) || 1);
        if (page > totalPages) page = totalPages;

        var start = (page - 1) * perPage;
        var end = start + perPage;

        cards.forEach(function (card) {
            card.hidden = true;
        });

        matches.forEach(function (card, index) {
            var onPage = index >= start && index < end;
            card.hidden = !onPage;
            if (onPage) loadCardImage(card);
        });

        if (empty) empty.hidden = total !== 0;

        if (countEl) {
            if (total === 0) {
                countEl.hidden = true;
            } else {
                var from = start + 1;
                var to = Math.min(end, total);
                countEl.hidden = false;
                countEl.textContent = 'Mostrando ' + from + '–' + to + ' de ' + total;
            }
        }

        renderPagination(totalPages);

        if (opts.scroll && grid) {
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    if (search) search.addEventListener('input', function () {
        page = 1;
        update();
    });

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeFilter = btn.getAttribute('data-ead-filter') || 'all';
            filterBtns.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
            page = 1;
            update({ scroll: true });
        });
    });

    update();
})();
</script>
@endpush
