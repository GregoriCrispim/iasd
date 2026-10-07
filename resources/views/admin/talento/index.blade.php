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
    <a href="{{ route('talento.show') }}" class="btn btn-secondary" target="_blank" rel="noopener">
        <i class="bi bi-box-arrow-up-right"></i> Formulário público
    </a>
@endsection

@section('content')
    <div class="stat-grid">
        <div class="stat">
            <i class="bi bi-people-fill stat-icon"></i>
            <div class="stat-label">Inscritos</div>
            <div class="stat-value">{{ number_format($stats['volunteers'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-star-fill stat-icon"></i>
            <div class="stat-label">Candidaturas à Liderança</div>
            <div class="stat-value">{{ number_format($stats['lideranca'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Candidaturas à Equipe</div>
            <div class="stat-value">{{ number_format($stats['equipe'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="card">
        <form method="GET" class="filters" id="volFilters">
            <input type="hidden" name="view" value="{{ $viewMode }}">

            <div class="field" style="flex:1;min-width:200px;">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ $q }}" class="input" placeholder="Nome, e-mail ou telefone…">
            </div>

            <div class="field">
                <label>Ministério</label>
                <select name="ministry" class="select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach ($ministries as $slug => $ministry)
                        <option value="{{ $slug }}" @selected($ministryFilter === $slug)>{{ is_array($ministry) ? $ministry['name'] : $ministry }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Modalidade</label>
                <select name="modality" class="select" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="lideranca" @selected($modalityFilter === 'lideranca')>Liderança</option>
                    <option value="equipe" @selected($modalityFilter === 'equipe')>Equipe</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div style="display:flex;gap:8px;flex-wrap:wrap;padding:12px 20px;border-bottom:1px solid var(--adm-border);">
            <a
                href="{{ route('admin.talento.index', array_merge($filterQuery, ['view' => 'lista'])) }}"
                class="btn btn-sm {{ $viewMode === 'lista' ? '' : 'btn-secondary' }}"
            >
                <i class="bi bi-list-ul"></i> Lista de inscritos
            </a>
            <a
                href="{{ route('admin.talento.index', array_merge($filterQuery, ['view' => 'ministerio'])) }}"
                class="btn btn-sm {{ $viewMode === 'ministerio' ? '' : 'btn-secondary' }}"
            >
                <i class="bi bi-grid-3x3-gap"></i> Por ministério
            </a>
        </div>

        @if ($viewMode === 'lista')
            <div class="table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>WhatsApp</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th>Escolhas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $app)
                            @php $link = $waLink($app->phone); @endphp
                            <tr>
                                <td><strong>{{ $app->name }}</strong></td>
                                <td>
                                    @if ($link)
                                        <a href="{{ $link }}" target="_blank" rel="noopener" title="Abrir no WhatsApp">
                                            <i class="bi bi-whatsapp" style="color:#16a34a;"></i> {{ $app->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ $app->phone }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    <a href="mailto:{{ $app->email }}">{{ $app->email }}</a>
                                </td>
                                <td class="text-muted" style="white-space:nowrap;">
                                    {{ $app->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
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
                                <td colspan="5">
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

            @if ($applications->hasPages())
                <div class="adm-pagination">{{ $applications->links() }}</div>
            @endif
        @else
            <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">
                @forelse ($byMinistry as $block)
                    <div style="border:1px solid var(--adm-border);border-radius:var(--adm-radius);overflow:hidden;">
                        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px;background:#f8fafc;border-bottom:1px solid var(--adm-border);">
                            <strong style="font-size:15px;">{{ $block['label'] }}</strong>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <span class="badge badge-amber">Liderança: {{ $block['lideranca'] }}</span>
                                <span class="badge badge-blue">Equipe: {{ $block['equipe'] }}</span>
                                <span class="badge badge-gray">Total: {{ $block['total'] }}</span>
                            </div>
                        </div>
                        @if ($block['total'] === 0)
                            <p class="text-muted" style="margin:0;padding:14px 16px;font-size:13px;">Nenhum candidato ainda.</p>
                        @else
                            <div class="table-wrap" style="border:none;">
                                <table class="adm-table">
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
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>Nenhum ministério para exibir.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
@endsection
