@extends('admin.layout')

@php
    $activeNav = 'talento';

    $waLink = function (string $phone): ?string {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return null;
        }
        if (! str_starts_with($digits, '55') && strlen($digits) >= 10 && strlen($digits) <= 11) {
            $digits = '55'.$digits;
        }
        return 'https://wa.me/'.$digits;
    };

    $filterQuery = array_filter([
        'q' => $q !== '' ? $q : null,
        'ministry' => $ministryFilter !== '' ? $ministryFilter : null,
        'modality' => $modalityFilter !== '' ? $modalityFilter : null,
    ], fn ($v) => $v !== null);
@endphp

@section('title', 'Meu Talento, Meu Ministério')
@section('heading', 'Meu Talento, Meu Ministério')

@section('actions')
    <a
        href="{{ route('admin.talento.pdf', $filterQuery) }}"
        class="btn talento-pdf-btn"
    >
        <i class="bi bi-file-earmark-pdf"></i> Relatório PDF
    </a>
    <a
        href="{{ route('talento.show') }}"
        class="btn btn-secondary talento-public-btn"
        target="_blank"
        rel="noopener"
    >
        <i class="bi bi-box-arrow-up-right"></i> Formulário público
    </a>
@endsection

@push('styles')
    <style>
        /* ===== Meu Talento — layout page-scoped ===== */
        .talento-page { min-width: 0; }

        .talento-view-tabs .tab-label-short { display: none; }

        .talento-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .talento-stat {
            position: relative;
            background: var(--adm-surface);
            border: 1px solid var(--adm-border);
            border-radius: var(--adm-radius);
            padding: 18px 20px;
            box-shadow: var(--adm-shadow);
        }
        .talento-stat__label {
            color: var(--adm-muted);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            padding-right: 36px;
        }
        .talento-stat__value {
            font-size: 30px;
            font-weight: 800;
            margin-top: 6px;
            letter-spacing: -0.02em;
            color: var(--adm-text);
            line-height: 1.1;
        }
        .talento-stat__icon {
            position: absolute;
            top: 16px;
            right: 16px;
            font-size: 26px;
            color: var(--adm-primary);
            opacity: .45;
            line-height: 1;
        }

        .talento-card { margin-bottom: 0; }
        .talento-filters {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(180px, 1fr) minmax(150px, .8fr);
            gap: 12px 14px;
            align-items: end;
            padding: 16px 20px;
            border-bottom: 1px solid var(--adm-border);
        }
        .talento-filters .field { min-width: 0; }

        /* Custom select */
        .adm-cselect {
            position: relative;
            width: 100%;
            z-index: 1;
        }
        .adm-cselect.is-open { z-index: 60; }
        .adm-cselect__native {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
            width: 100%;
            height: 100%;
        }
        .adm-cselect__trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid var(--adm-border);
            border-radius: 9px;
            background: #fff;
            color: var(--adm-text);
            font: inherit;
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .adm-cselect__trigger:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .adm-cselect__trigger:focus-visible {
            outline: none;
            border-color: var(--adm-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }
        .adm-cselect.is-open .adm-cselect__trigger {
            border-color: var(--adm-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
            background: #fff;
        }
        .adm-cselect__value {
            min-width: 0;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .adm-cselect__value.is-placeholder { color: var(--adm-muted); font-weight: 400; }
        .adm-cselect__chevron {
            flex: 0 0 auto;
            color: var(--adm-muted);
            font-size: 14px;
            line-height: 1;
            transition: transform .18s ease;
        }
        .adm-cselect.is-open .adm-cselect__chevron { transform: rotate(180deg); color: var(--adm-primary); }

        .adm-cselect__menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 50;
            margin: 0;
            padding: 6px;
            list-style: none;
            background: #fff;
            border: 1px solid var(--adm-border);
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, .14), 0 2px 6px rgba(15, 23, 42, .06);
            max-height: min(280px, 50vh);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }
        .adm-cselect__menu[hidden] { display: none; }

        .adm-cselect__option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--adm-text);
            font: inherit;
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: background .12s, color .12s;
        }
        .adm-cselect__option:hover,
        .adm-cselect__option:focus-visible,
        .adm-cselect__option.is-active {
            background: #eff6ff;
            color: #1d4ed8;
            outline: none;
        }
        .adm-cselect__option.is-selected {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
        }
        .adm-cselect__option-label {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .talento-pdf-btn {
            background: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }
        .talento-pdf-btn:hover {
            background: #991b1b;
            border-color: #991b1b;
            color: #fff;
        }
        .talento-pdf-btn-mobile { display: none; }

        .talento-view-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 12px 20px;
            border-bottom: 1px solid var(--adm-border);
            background: #fafbfc;
        }
        .talento-view-tabs .btn {
            flex: 0 1 auto;
        }
        .talento-view-tabs .btn.is-active {
            background: var(--adm-primary);
            color: #fff;
            border-color: var(--adm-primary);
        }
        .talento-view-tabs .btn.is-active:hover {
            background: var(--adm-primary-dark);
            color: #fff;
        }

        .talento-choice-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* Desktop table / mobile cards */
        .talento-desktop-table { display: block; }
        .talento-mobile-list { display: none; }

        .talento-app-card {
            padding: 16px 18px;
            border-bottom: 1px solid var(--adm-border);
        }
        .talento-app-card:last-child { border-bottom: 0; }
        .talento-app-card__top {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-bottom: 10px;
            min-width: 0;
        }
        .talento-app-card__name {
            font-size: 15px;
            font-weight: 700;
            color: var(--adm-text);
            line-height: 1.3;
            word-break: break-word;
        }
        .talento-app-card__date,
        .talento-name-cell__date {
            font-size: 12px;
            color: var(--adm-muted);
            white-space: nowrap;
            margin-top: 2px;
        }
        .talento-name-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }
        .talento-name-cell strong {
            line-height: 1.3;
            word-break: break-word;
        }
        .talento-wa-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            line-height: 1.2;
        }
        .talento-wa-link .bi-whatsapp {
            color: #16a34a;
            font-size: 15px;
            flex: 0 0 auto;
            line-height: 1;
        }
        .talento-app-card__meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px;
        }
        .talento-app-card__meta a,
        .talento-app-card__meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: var(--adm-muted);
            word-break: break-word;
        }
        .talento-app-card__meta a:hover { color: var(--adm-primary); }
        .talento-app-card__meta .bi-whatsapp { color: #16a34a; font-size: 15px; }
        .talento-app-card__meta .bi-envelope,
        .talento-app-card__meta .bi-telephone { font-size: 14px; opacity: .75; }

        .talento-ministry-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 16px 20px 20px;
        }
        .talento-ministry-block {
            border: 1px solid var(--adm-border);
            border-radius: var(--adm-radius);
            overflow: hidden;
            background: #fff;
        }
        .talento-ministry-block__head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            background: #f8fafc;
            border-bottom: 1px solid var(--adm-border);
        }
        .talento-ministry-block__title {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
            min-width: 0;
            word-break: break-word;
        }
        .talento-ministry-block__counts {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .talento-ministry-block__empty {
            margin: 0;
            padding: 14px 16px;
            font-size: 13px;
        }
        .talento-ministry-block .table-wrap { border: none; }
        .talento-ministry-table {
            table-layout: fixed;
            width: 100%;
        }
        .talento-ministry-col-name { width: auto; }
        .talento-ministry-col-modality { width: 140px; }
        .talento-ministry-table th:last-child,
        .talento-ministry-table td:last-child {
            width: 140px;
            text-align: right;
            white-space: nowrap;
            vertical-align: middle;
        }
        .talento-ministry-table td:last-child .badge {
            justify-content: center;
        }
        .talento-ministry-table td:first-child {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .talento-candidate-list { display: none; }
        .talento-candidate-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--adm-border);
        }
        .talento-candidate-item:last-child { border-bottom: 0; }
        .talento-candidate-item__name {
            font-size: 14px;
            font-weight: 600;
            min-width: 0;
            flex: 1 1 auto;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .talento-candidate-item .badge {
            flex: 0 0 auto;
            min-width: 88px;
            justify-content: center;
        }

        @media (max-width: 1100px) {
            .talento-filters {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }
            .talento-filters__search { grid-column: 1 / -1; }
        }

        @media (max-width: 900px) {
            .talento-stat-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 16px;
            }
            .talento-stat {
                padding: 16px 18px;
            }
            .talento-stat__value { font-size: 28px; }
        }

        @media (max-width: 900px) {
            .talento-public-btn,
            .adm-topbar .adm-actions { display: none !important; }
            .talento-pdf-btn-mobile { display: inline-flex; }
            .adm-topbar {
                grid-template-columns: auto minmax(0, 1fr);
                grid-template-areas: "burger title";
            }
        }

        @media (max-width: 720px) {
            .talento-filters {
                grid-template-columns: 1fr;
                padding: 14px 16px;
                gap: 12px;
            }
            .talento-filters__search { grid-column: auto; }

            .talento-view-tabs {
                padding: 10px 12px;
                gap: 8px;
                flex-wrap: nowrap;
            }
            .talento-view-tabs .btn {
                flex: 1 1 0;
                justify-content: center;
                min-width: 0;
                padding-left: 10px;
                padding-right: 10px;
                white-space: nowrap;
            }
            .talento-view-tabs .btn span.tab-label-full { display: none; }
            .talento-view-tabs .tab-label-long { display: none; }
            .talento-view-tabs .tab-label-short { display: inline; }

            .talento-desktop-table { display: none; }
            .talento-mobile-list { display: block; }

            .talento-ministry-list { padding: 12px 12px 16px; gap: 12px; }
            .talento-ministry-block__head {
                flex-direction: column;
                align-items: flex-start;
                padding: 12px 14px;
            }
            .talento-ministry-block .talento-desktop-table { display: none; }
            .talento-candidate-list { display: block; }
        }

        @media (max-width: 420px) {
            .talento-stat__label { font-size: 12.5px; }
            .talento-stat__value { font-size: 26px; }
            .talento-app-card { padding: 14px 14px; }
        }
    </style>
@endpush

@section('content')
    <div class="talento-page">
        <div class="talento-stat-grid">
            <div class="talento-stat">
                <i class="bi bi-people-fill talento-stat__icon" aria-hidden="true"></i>
                <div class="talento-stat__label">Inscritos</div>
                <div class="talento-stat__value">{{ number_format($stats['volunteers'], 0, ',', '.') }}</div>
            </div>
            <div class="talento-stat">
                <i class="bi bi-star-fill talento-stat__icon" aria-hidden="true"></i>
                <div class="talento-stat__label">Candidaturas à Liderança</div>
                <div class="talento-stat__value">{{ number_format($stats['lideranca'], 0, ',', '.') }}</div>
            </div>
            <div class="talento-stat">
                <i class="bi bi-people talento-stat__icon" aria-hidden="true"></i>
                <div class="talento-stat__label">Candidaturas à Equipe</div>
                <div class="talento-stat__value">{{ number_format($stats['equipe'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="card talento-card">
            <form method="GET" class="talento-filters" id="volFilters">
                <input type="hidden" name="view" value="{{ $viewMode }}">

                <div class="field talento-filters__search">
                    <label for="talento-q">Buscar</label>
                    <input
                        type="search"
                        id="talento-q"
                        name="q"
                        value="{{ $q }}"
                        class="input"
                        placeholder="Nome, e-mail ou telefone…"
                        enterkeyhint="search"
                    >
                </div>

                <div class="field">
                    <label id="talento-ministry-label">Ministério</label>
                    <div class="adm-cselect" data-cselect data-autosubmit="1">
                        <select id="talento-ministry" name="ministry" class="adm-cselect__native" tabindex="-1" aria-hidden="true">
                            <option value="" @selected($ministryFilter === '')>Todos</option>
                            @foreach ($ministries as $slug => $ministry)
                                <option value="{{ $slug }}" @selected($ministryFilter === $slug)>{{ is_array($ministry) ? $ministry['name'] : $ministry }}</option>
                            @endforeach
                        </select>
                        <button
                            type="button"
                            class="adm-cselect__trigger"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-labelledby="talento-ministry-label"
                        >
                            <span class="adm-cselect__value">Todos</span>
                            <i class="bi bi-chevron-down adm-cselect__chevron" aria-hidden="true"></i>
                        </button>
                        <ul class="adm-cselect__menu" role="listbox" aria-labelledby="talento-ministry-label" hidden></ul>
                    </div>
                </div>

                <div class="field">
                    <label id="talento-modality-label">Modalidade</label>
                    <div class="adm-cselect" data-cselect data-autosubmit="1">
                        <select id="talento-modality" name="modality" class="adm-cselect__native" tabindex="-1" aria-hidden="true">
                            <option value="" @selected($modalityFilter === '')>Todas</option>
                            <option value="lideranca" @selected($modalityFilter === 'lideranca')>Liderança</option>
                            <option value="equipe" @selected($modalityFilter === 'equipe')>Equipe</option>
                        </select>
                        <button
                            type="button"
                            class="adm-cselect__trigger"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-labelledby="talento-modality-label"
                        >
                            <span class="adm-cselect__value">Todas</span>
                            <i class="bi bi-chevron-down adm-cselect__chevron" aria-hidden="true"></i>
                        </button>
                        <ul class="adm-cselect__menu" role="listbox" aria-labelledby="talento-modality-label" hidden></ul>
                    </div>
                </div>

            </form>

            <div class="talento-view-tabs" role="tablist" aria-label="Modo de visualização">
                <a
                    href="{{ route('admin.talento.index', array_merge($filterQuery, ['view' => 'lista'])) }}"
                    class="btn btn-sm {{ $viewMode === 'lista' ? 'is-active' : 'btn-secondary' }}"
                    role="tab"
                    aria-selected="{{ $viewMode === 'lista' ? 'true' : 'false' }}"
                >
                    <i class="bi bi-list-ul"></i>
                    <span>Lista<span class="tab-label-full"> de inscritos</span></span>
                </a>
                <a
                    href="{{ route('admin.talento.index', array_merge($filterQuery, ['view' => 'ministerio'])) }}"
                    class="btn btn-sm {{ $viewMode === 'ministerio' ? 'is-active' : 'btn-secondary' }}"
                    role="tab"
                    aria-selected="{{ $viewMode === 'ministerio' ? 'true' : 'false' }}"
                >
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span class="tab-label-long">Por ministério</span>
                    <span class="tab-label-short">Ministérios</span>
                </a>
                <a
                    href="{{ route('admin.talento.pdf', $filterQuery) }}"
                    class="btn btn-sm talento-pdf-btn talento-pdf-btn-mobile"
                >
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>PDF</span>
                </a>
            </div>

            @if ($viewMode === 'lista')
                <div class="table-wrap talento-desktop-table">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>WhatsApp</th>
                                <th>E-mail</th>
                                <th>Escolhas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($applications as $app)
                                @php $link = $waLink($app->phone); @endphp
                                <tr>
                                    <td>
                                        <div class="talento-name-cell">
                                            <strong>{{ $app->name }}</strong>
                                            <span class="talento-name-cell__date">
                                                {{ $app->created_at?->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($link)
                                            <a href="{{ $link }}" class="talento-wa-link" target="_blank" rel="noopener" title="Abrir no WhatsApp">
                                                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                                <span>{{ $app->phone }}</span>
                                            </a>
                                        @else
                                            <span class="text-muted">{{ $app->phone }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        <a href="mailto:{{ $app->email }}">{{ $app->email }}</a>
                                    </td>
                                    <td>
                                        <div class="talento-choice-badges">
                                            @foreach ($app->choices as $choice)
                                                <span class="badge {{ $choice->isLideranca() ? 'badge-amber' : 'badge-blue' }}">
                                                    {{ $choice->ministryLabel() }} · {{ $choice->modalityLabel() }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Nenhuma inscrição encontrada com esses filtros.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="talento-mobile-list">
                    @forelse ($applications as $app)
                        @php $link = $waLink($app->phone); @endphp
                        <article class="talento-app-card">
                            <div class="talento-app-card__top">
                                <div class="talento-app-card__name">{{ $app->name }}</div>
                                <div class="talento-app-card__date">
                                    {{ $app->created_at?->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="talento-app-card__meta">
                                @if ($link)
                                    <a href="{{ $link }}" class="talento-wa-link" target="_blank" rel="noopener">
                                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                        <span>{{ $app->phone }}</span>
                                    </a>
                                @else
                                    <span class="talento-wa-link text-muted">
                                        <i class="bi bi-telephone" aria-hidden="true"></i>
                                        <span>{{ $app->phone }}</span>
                                    </span>
                                @endif
                                <a href="mailto:{{ $app->email }}">
                                    <i class="bi bi-envelope" aria-hidden="true"></i>
                                    {{ $app->email }}
                                </a>
                            </div>
                            <div class="talento-choice-badges">
                                @foreach ($app->choices as $choice)
                                    <span class="badge {{ $choice->isLideranca() ? 'badge-amber' : 'badge-blue' }}">
                                        {{ $choice->ministryLabel() }} · {{ $choice->modalityLabel() }}
                                    </span>
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Nenhuma inscrição encontrada com esses filtros.</p>
                        </div>
                    @endforelse
                </div>

                @if ($applications->hasPages())
                    <div class="adm-pagination">{{ $applications->links() }}</div>
                @endif
            @else
                <div class="talento-ministry-list">
                    @forelse ($byMinistry as $block)
                        <section class="talento-ministry-block">
                            <header class="talento-ministry-block__head">
                                <h2 class="talento-ministry-block__title">{{ $block['label'] }}</h2>
                                <div class="talento-ministry-block__counts">
                                    <span class="badge badge-amber">Liderança: {{ $block['lideranca'] }}</span>
                                    <span class="badge badge-blue">Equipe: {{ $block['equipe'] }}</span>
                                    <span class="badge badge-gray">Total: {{ $block['total'] }}</span>
                                </div>
                            </header>
                            @if ($block['total'] === 0)
                                <p class="text-muted talento-ministry-block__empty">Nenhum candidato ainda.</p>
                            @else
                                <div class="table-wrap talento-desktop-table">
                                    <table class="adm-table talento-ministry-table">
                                        <colgroup>
                                            <col class="talento-ministry-col-name">
                                            <col class="talento-ministry-col-modality">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Candidato</th>
                                                <th>Modalidade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($block['candidates'] as $candidate)
                                                <tr>
                                                    <td>{{ $candidate['name'] }}</td>
                                                    <td>
                                                        <span class="badge {{ $candidate['modality'] === 'lideranca' ? 'badge-amber' : 'badge-blue' }}">
                                                            {{ $candidate['modality_label'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="talento-candidate-list">
                                    @foreach ($block['candidates'] as $candidate)
                                        <div class="talento-candidate-item">
                                            <span class="talento-candidate-item__name">{{ $candidate['name'] }}</span>
                                            <span class="badge {{ $candidate['modality'] === 'lideranca' ? 'badge-amber' : 'badge-blue' }}">
                                                {{ $candidate['modality_label'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Nenhum ministério para exibir.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    function closeAll(except) {
        document.querySelectorAll('.adm-cselect.is-open').forEach(function (el) {
            if (el !== except) closeSelect(el);
        });
    }

    function closeSelect(root) {
        root.classList.remove('is-open');
        var trigger = root.querySelector('.adm-cselect__trigger');
        var menu = root.querySelector('.adm-cselect__menu');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
        if (menu) menu.hidden = true;
        root.querySelectorAll('.adm-cselect__option.is-active').forEach(function (opt) {
            opt.classList.remove('is-active');
        });
    }

    function openSelect(root) {
        closeAll(root);
        root.classList.add('is-open');
        var trigger = root.querySelector('.adm-cselect__trigger');
        var menu = root.querySelector('.adm-cselect__menu');
        if (trigger) trigger.setAttribute('aria-expanded', 'true');
        if (menu) menu.hidden = false;

        var selected = root.querySelector('.adm-cselect__option.is-selected');
        if (selected) {
            selected.classList.add('is-active');
            selected.focus({ preventScroll: true });
            selected.scrollIntoView({ block: 'nearest' });
        }
    }

    function setValue(root, value, label, submit) {
        var select = root.querySelector('.adm-cselect__native');
        var valueEl = root.querySelector('.adm-cselect__value');
        if (!select || !valueEl) return;

        select.value = value;
        valueEl.textContent = label;
        valueEl.classList.toggle('is-placeholder', value === '');

        root.querySelectorAll('.adm-cselect__option').forEach(function (opt) {
            var isSelected = opt.getAttribute('data-value') === value;
            opt.classList.toggle('is-selected', isSelected);
            opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
        });

        closeSelect(root);

        if (submit && root.getAttribute('data-autosubmit') === '1') {
            var form = root.closest('form');
            if (form) form.submit();
        }
    }

    function buildOptions(root) {
        var select = root.querySelector('.adm-cselect__native');
        var menu = root.querySelector('.adm-cselect__menu');
        var valueEl = root.querySelector('.adm-cselect__value');
        if (!select || !menu || !valueEl) return;

        menu.innerHTML = '';
        var currentLabel = '';

        Array.prototype.forEach.call(select.options, function (option) {
            var li = document.createElement('li');
            li.setAttribute('role', 'presentation');

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'adm-cselect__option';
            btn.setAttribute('role', 'option');
            btn.setAttribute('data-value', option.value);
            btn.setAttribute('aria-selected', option.selected ? 'true' : 'false');
            if (option.selected) {
                btn.classList.add('is-selected');
                currentLabel = option.textContent;
            }

            var label = document.createElement('span');
            label.className = 'adm-cselect__option-label';
            label.textContent = option.textContent;
            btn.appendChild(label);

            btn.addEventListener('click', function () {
                setValue(root, option.value, option.textContent, true);
            });

            li.appendChild(btn);
            menu.appendChild(li);
        });

        valueEl.textContent = currentLabel || (select.options[0] ? select.options[0].textContent : '');
        valueEl.classList.toggle('is-placeholder', select.value === '');
    }

    function moveActive(root, delta) {
        var options = Array.prototype.slice.call(root.querySelectorAll('.adm-cselect__option'));
        if (!options.length) return;
        var idx = options.findIndex(function (opt) { return opt.classList.contains('is-active'); });
        if (idx < 0) idx = options.findIndex(function (opt) { return opt.classList.contains('is-selected'); });
        if (idx < 0) idx = 0;
        else idx = (idx + delta + options.length) % options.length;

        options.forEach(function (opt) { opt.classList.remove('is-active'); });
        options[idx].classList.add('is-active');
        options[idx].focus({ preventScroll: true });
        options[idx].scrollIntoView({ block: 'nearest' });
    }

    document.querySelectorAll('[data-cselect]').forEach(function (root) {
        buildOptions(root);

        var trigger = root.querySelector('.adm-cselect__trigger');
        if (!trigger) return;

        trigger.addEventListener('click', function () {
            if (root.classList.contains('is-open')) closeSelect(root);
            else openSelect(root);
        });

        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openSelect(root);
            }
        });

        root.addEventListener('keydown', function (e) {
            if (!root.classList.contains('is-open')) return;
            if (e.key === 'Escape') {
                e.preventDefault();
                closeSelect(root);
                trigger.focus();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                moveActive(root, 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                moveActive(root, -1);
            } else if (e.key === 'Enter') {
                var active = root.querySelector('.adm-cselect__option.is-active');
                if (active) {
                    e.preventDefault();
                    active.click();
                }
            }
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-cselect]')) closeAll();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll();
    });

    var form = document.getElementById('volFilters');
    var search = document.getElementById('talento-q');
    if (form && search) {
        var searchTimer = null;
        var lastSubmitted = search.value;

        function submitSearch() {
            if (search.value === lastSubmitted) return;
            lastSubmitted = search.value;
            form.submit();
        }

        search.addEventListener('input', function () {
            if (searchTimer) clearTimeout(searchTimer);
            searchTimer = setTimeout(submitSearch, 400);
        });

        search.addEventListener('search', function () {
            if (searchTimer) clearTimeout(searchTimer);
            submitSearch();
        });
    }
})();
</script>
@endpush
