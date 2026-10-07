@extends('layouts.solo')

@section('title', 'Meu Talento, Meu Ministério')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/talento.css') }}?v={{ filemtime(public_path('css/talento.css')) }}">
@endpush

@section('content')
<div class="vol-shell">
    <div class="vol-orb vol-orb--a" aria-hidden="true"></div>
    <div class="vol-orb vol-orb--b" aria-hidden="true"></div>

    <div class="vol-wrap">
        <header class="vol-brand">
            <div class="vol-brand-mark">
                <img src="{{ asset('img/logo_iasd.png') }}" alt="IASD Central Brasília" onerror="this.style.display='none'">
            </div>
            <p class="vol-eyebrow">IASD Central Brasília</p>
            <h1>Meu Talento,<br><em>Meu Ministério</em></h1>
            <p class="vol-tagline">Compartilhe seus dons e escolha até 3 ministérios onde gostaria de servir.</p>
        </header>

        @if ($success)
            <div class="vol-panel vol-success" role="status">
                <div class="vol-success-icon" aria-hidden="true">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2>Inscrição enviada!</h2>
                <p>
                    Obrigado, <strong>{{ $success['name'] }}</strong>.
                    Recebemos sua disposição com alegria — em breve a liderança entra em contato.
                </p>

                <div class="vol-success-summary">
                    <h3>Seus ministérios</h3>
                    <ul class="vol-success-list">
                        @foreach ($success['choices'] as $choice)
                            <li>
                                <span class="vol-success-dot" aria-hidden="true"></span>
                                <span class="name">{{ $choice['ministry'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('talento.show') }}" class="vol-btn-secondary">
                    <i class="bi bi-person-plus"></i> Nova inscrição
                </a>
            </div>
        @else
            @if ($errors->any())
                <div class="vol-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form id="volForm" method="POST" action="{{ route('talento.store') }}" novalidate>
                @csrf
                <div id="volChoicesInputs" hidden></div>

                <section class="vol-panel" aria-labelledby="vol-dados-title">
                    <div class="vol-panel-head">
                        <span class="vol-step">1</span>
                        <div>
                            <h2 id="vol-dados-title">Sobre você</h2>
                            <p class="vol-lead">Só o essencial para podermos falar com você.</p>
                        </div>
                    </div>

                    <div class="vol-fields">
                        <div class="vol-field">
                            <label for="name">Nome completo</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="vol-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                autocomplete="name"
                                required
                                maxlength="255"
                                placeholder="Como você gostaria de ser chamado"
                                data-validate="name"
                                aria-describedby="error-name"
                            >
                            <p class="vol-error" id="error-name" data-error-for="name" role="alert" @error('name') @else hidden @enderror>
                                @error('name'){{ $message }}@enderror
                            </p>
                        </div>

                        <div class="vol-field-row">
                            <div class="vol-field">
                                <label for="phone">WhatsApp</label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    class="vol-input @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}"
                                    data-mask="br-phone"
                                    data-validate="phone"
                                    inputmode="tel"
                                    autocomplete="tel"
                                    placeholder="(61) 99999-9999"
                                    required
                                    maxlength="20"
                                    aria-describedby="error-phone"
                                >
                                <p class="vol-error" id="error-phone" data-error-for="phone" role="alert" @error('phone') @else hidden @enderror>
                                    @error('phone'){{ $message }}@enderror
                                </p>
                            </div>

                            <div class="vol-field">
                                <label for="email">E-mail</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="vol-input @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    data-validate="email"
                                    autocomplete="email"
                                    placeholder="seu@email.com"
                                    required
                                    maxlength="255"
                                    aria-describedby="error-email"
                                >
                                <p class="vol-error" id="error-email" data-error-for="email" role="alert" @error('email') @else hidden @enderror>
                                    @error('email'){{ $message }}@enderror
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="vol-panel" aria-labelledby="vol-min-title">
                    <div class="vol-panel-head">
                        <span class="vol-step">2</span>
                        <div>
                            <h2 id="vol-min-title">Onde você quer servir</h2>
                            <p class="vol-lead">Toque em Liderança ou Equipe em cada ministério. Até 3 opções.</p>
                        </div>
                    </div>

                    <div class="vol-search-wrap">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input
                            type="search"
                            id="volSearch"
                            class="vol-input"
                            placeholder="Buscar por nome…"
                            autocomplete="off"
                            enterkeyhint="search"
                        >
                    </div>

                    <div id="volMinistryList" class="vol-ministry-list">
                        @foreach ($ministries as $slug => $ministry)
                            @php
                                $label = is_array($ministry) ? ($ministry['name'] ?? $slug) : $ministry;
                                $description = is_array($ministry) ? ($ministry['description'] ?? '') : '';
                            @endphp
                            <article
                                class="vol-ministry"
                                data-slug="{{ $slug }}"
                                data-name="{{ $label }}"
                            >
                                <div class="vol-ministry-top">
                                    <h3 class="vol-ministry-name" id="min-title-{{ $slug }}">{{ $label }}</h3>
                                    <button
                                        type="button"
                                        class="vol-info-btn"
                                        data-accordion-toggle
                                        aria-expanded="false"
                                        aria-controls="min-desc-{{ $slug }}"
                                        title="Ver atribuições"
                                    >
                                        <span class="vol-info-label">Ver atribuições</span>
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <div
                                    class="vol-accordion"
                                    id="min-desc-{{ $slug }}"
                                    role="region"
                                    aria-labelledby="min-title-{{ $slug }}"
                                    aria-hidden="true"
                                >
                                    <div class="vol-accordion-inner">
                                        <p class="vol-ministry-desc">{{ $description }}</p>
                                    </div>
                                </div>

                                <div class="vol-modality" role="group" aria-label="Como servir em {{ $label }}">
                                    <button
                                        type="button"
                                        class="vol-mod-btn"
                                        data-modality="lideranca"
                                        aria-pressed="false"
                                    >
                                        Liderança
                                    </button>
                                    <button
                                        type="button"
                                        class="vol-mod-btn"
                                        data-modality="equipe"
                                        aria-pressed="false"
                                    >
                                        Equipe
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <p id="volEmptyFilter" class="vol-empty-filter">Nenhum ministério com esse nome. Tente outra busca.</p>
                </section>

                <section id="volSubmitZone" class="vol-panel vol-submit-zone" aria-live="polite">
                    <div id="volDoneMsg" class="vol-done-msg" hidden>
                        <div class="vol-done-icon" aria-hidden="true"><i class="bi bi-stars"></i></div>
                        <div>
                            <strong>Pronto — você já escolheu os 3!</strong>
                            <p>Quando quiser, é só enviar. Se mudar de ideia, dá para trocar algum ministério acima.</p>
                        </div>
                    </div>

                    <button type="submit" id="volSubmit" class="vol-submit" disabled>
                        <span class="spinner" aria-hidden="true"></span>
                        <span class="btn-label">Enviar inscrição</span>
                    </button>
                    <p class="vol-submit-hint" id="volSubmitHint">Escolha pelo menos 1 ministério para continuar.</p>
                </section>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    @unless ($success)
        <script src="{{ asset('js/talento.js') }}?v={{ filemtime(public_path('js/talento.js')) }}"></script>
    @endunless
@endpush
