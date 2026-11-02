@extends('admin.layout')

@php $activeNav = 'analytics'; @endphp
@section('title', 'Tráfego do site')
@section('heading', 'Tráfego do site')

@section('actions')
    @if ($includeBots)
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-secondary">
            <i class="bi bi-funnel"></i> Só humanos
        </a>
    @else
        <a href="{{ route('admin.analytics.index', ['bots' => 1]) }}" class="btn btn-secondary">
            <i class="bi bi-robot"></i> Incluir bots
        </a>
    @endif
@endsection

@section('content')
    <p class="text-muted" style="margin-top:0;margin-bottom:20px;">
        Métricas server-side por rota e formulário.
        @if ($includeBots)
            Exibindo humanos e bots.
        @else
            Bots, crawlers e prefetch ficam fora dos totais ({{ number_format($stats['bots_total'], 0, ',', '.') }} bots registrados para auditoria).
        @endif
    </p>

    <div class="stat-grid">
        <div class="stat">
            <i class="bi bi-eye stat-icon"></i>
            <div class="stat-label">Visualizações</div>
            <div class="stat-value">{{ number_format($stats['views_total'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Visitantes únicos</div>
            <div class="stat-value">{{ number_format($stats['views_unique'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-calendar-day stat-icon"></i>
            <div class="stat-label">Hoje</div>
            <div class="stat-value">{{ number_format($stats['views_today'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-calendar-week stat-icon"></i>
            <div class="stat-label">Últimos 7 dias</div>
            <div class="stat-value">{{ number_format($stats['views_last7'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-ui-checks stat-icon"></i>
            <div class="stat-label">Envios de forms</div>
            <div class="stat-value">{{ number_format($stats['forms_total'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-send-check stat-icon"></i>
            <div class="stat-label">Forms hoje / 7d</div>
            <div class="stat-value">{{ number_format($stats['forms_today'], 0, ',', '.') }} <span style="font-size:14px;color:var(--adm-muted);font-weight:500;">/ {{ number_format($stats['forms_last7'], 0, ',', '.') }}</span></div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:24px;">
        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Por dispositivo</h2></div>
            <div class="card-body">
                @if (empty($byDevice))
                    <p class="text-muted mt-0 mb-0">Nenhuma visita ainda.</p>
                @else
                    <div class="table-wrap">
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th>Dispositivo</th>
                                    <th class="text-right">Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($byDevice as $device => $count)
                                    <tr>
                                        <td>{{ $device ?: 'unknown' }}</td>
                                        <td class="text-right">{{ number_format($count, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Por hora do dia</h2></div>
            <div class="card-body">
                <div style="display:flex;align-items:flex-end;gap:3px;height:120px;">
                    @foreach ($byHour as $hour => $count)
                        @php $pct = max(4, (int) round(($count / $maxHour) * 100)); @endphp
                        <div title="{{ sprintf('%02d', $hour) }}h: {{ $count }}" style="flex:1;background:var(--adm-primary);opacity:{{ $count ? '0.85' : '0.15' }};height:{{ $pct }}%;border-radius:3px 3px 0 0;"></div>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:11px;color:var(--adm-muted);">
                    <span>00h</span>
                    <span>12h</span>
                    <span>23h</span>
                </div>
            </div>
        </div>
    </div>

    @php
        $byDayLabels = [];
        $byDayValues = [];
        $formsDayValues = [];
        foreach ($byDay as $day => $count) {
            $byDayLabels[] = \Carbon\Carbon::parse($day)->format('d/m');
            $byDayValues[] = (int) $count;
            $formsDayValues[] = (int) ($formsByDay[$day] ?? 0);
        }
    @endphp
    <div class="card" style="margin-bottom:24px;">
        <div class="card-head"><h2>Últimos 30 dias</h2></div>
        <div class="card-body">
            <div class="analytics-day-chart-wrap">
                <canvas
                    id="analyticsByDayChart"
                    aria-label="Gráfico de visualizações e formulários nos últimos 30 dias"
                    role="img"
                    height="110"
                ></canvas>
            </div>
        </div>
    </div>
    @php
        $analyticsChartPayload = [
            'labels' => $byDayLabels,
            'views' => $byDayValues,
            'forms' => $formsDayValues,
        ];
    @endphp
    <script type="application/json" id="analyticsByDayData">@json($analyticsChartPayload)</script>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-bottom:24px;">
        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Tráfego por rota</h2></div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Rota / caminho</th>
                                <th class="text-right">Views</th>
                                <th class="text-right">Únicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($byRoute as $row)
                                <tr>
                                    <td>
                                        <code style="font-size:12px;">{{ $row->route_key }}</code>
                                        @if ($row->sample_path && $row->sample_path !== $row->route_key)
                                            <div class="text-muted" style="font-size:12px;">{{ $row->sample_path }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($row->total, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($row->uniques, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">Nenhuma visualização registrada ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Formulários</h2></div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Formulário</th>
                                <th class="text-right">Envios</th>
                                <th class="text-right">Únicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($byForm as $row)
                                <tr>
                                    <td>{{ $row->label }}</td>
                                    <td class="text-right">{{ number_format($row->total, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($row->uniques, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">Nenhum envio registrado ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;">
        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Visitas recentes</h2></div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Quando</th>
                                <th>Rota</th>
                                <th>Dispositivo</th>
                                @if ($includeBots)<th>Bot</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentViews as $visit)
                                <tr>
                                    <td>{{ $visit->viewed_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <code style="font-size:12px;">{{ $visit->route_name ?: $visit->path }}</code>
                                    </td>
                                    <td>{{ $visit->device_type ?? '—' }} · {{ $visit->browser ?? '—' }}</td>
                                    @if ($includeBots)
                                        <td>{{ $visit->is_bot ? 'sim' : 'não' }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $includeBots ? 4 : 3 }}" class="text-muted">Nenhuma visita recente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Envios recentes</h2></div>
            <div class="card-body" style="padding:0;">
                <div class="table-wrap">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Quando</th>
                                <th>Formulário</th>
                                <th>Dispositivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentForms as $submission)
                                <tr>
                                    <td>{{ $submission->submitted_at?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $submission->label() }}</td>
                                    <td>{{ $submission->device_type ?? '—' }} · {{ $submission->browser ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">Nenhum envio recente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .analytics-day-chart-wrap {
        position: relative;
        width: 100%;
        min-height: 280px;
    }
    .analytics-day-chart-wrap canvas {
        width: 100% !important;
        max-height: 320px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function () {
    var canvas = document.getElementById('analyticsByDayChart');
    var dataEl = document.getElementById('analyticsByDayData');
    if (!canvas || !dataEl || typeof Chart === 'undefined') return;

    var payload;
    try {
        payload = JSON.parse(dataEl.textContent || '{}');
    } catch (e) {
        return;
    }

    var labels = payload.labels || [];
    var views = payload.views || [];
    var forms = payload.forms || [];
    var ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Visualizações',
                    data: views,
                    borderColor: '#4299e1',
                    backgroundColor: 'rgba(66, 153, 225, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#4299e1',
                },
                {
                    label: 'Formulários',
                    data: forms,
                    borderColor: '#38a169',
                    backgroundColor: 'rgba(56, 161, 105, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#38a169',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: { boxWidth: 12, color: '#64748b', font: { size: 12 } }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f8fafc',
                    bodyColor: '#e2e8f0',
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10,
                        font: { size: 11 }
                    },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grace: '8%',
                    ticks: {
                        color: '#94a3b8',
                        precision: 0,
                        font: { size: 11 }
                    },
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false
                    },
                    border: { display: false }
                }
            }
        }
    });
})();
</script>
@endpush
