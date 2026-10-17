@extends('admin.layout')

@php $activeNav = 'campanhas'; @endphp
@section('title', $campaign->name)
@section('heading', $campaign->name)

@section('actions')
    <a href="{{ route('estudo-biblico') }}" class="btn btn-secondary" target="_blank" rel="noopener">
        <i class="bi bi-box-arrow-up-right"></i> Ver destino
    </a>
@endsection

@section('content')
    <div class="card" style="margin-bottom:24px;">
        <div class="card-head"><h2>URL do QR Code</h2></div>
        <div class="card-body">
            <p class="text-muted mt-0" style="margin-bottom:12px;">
                Gere o QR Code apontando para esta URL. Cada scan é contabilizado e o visitante é redirecionado para o Estudo Bíblico.
            </p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                <code id="campaignTrackingUrl" style="flex:1;min-width:220px;padding:10px 14px;background:#f8fafc;border:1px solid var(--adm-border);border-radius:9px;font-size:14px;word-break:break-all;">{{ $trackingUrl }}</code>
                <button type="button" class="btn btn-secondary" id="copyTrackingUrl">
                    <i class="bi bi-clipboard"></i> Copiar
                </button>
            </div>
            <p class="text-muted" style="margin:12px 0 0;font-size:13px;">
                Status:
                @if ($campaign->is_active)
                    <span style="color:var(--adm-success);font-weight:600;">ativa</span>
                @else
                    <span style="color:var(--adm-danger);font-weight:600;">inativa</span>
                @endif
                · Destino: <code>{{ $campaign->destination_route }}</code>
            </p>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat">
            <i class="bi bi-qr-code-scan stat-icon"></i>
            <div class="stat-label">Total de scans</div>
            <div class="stat-value">{{ number_format($stats['total'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Visitantes únicos</div>
            <div class="stat-value">{{ number_format($stats['unique'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-calendar-day stat-icon"></i>
            <div class="stat-label">Hoje</div>
            <div class="stat-value">{{ number_format($stats['today'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <i class="bi bi-calendar-week stat-icon"></i>
            <div class="stat-label">Últimos 7 dias</div>
            <div class="stat-value">{{ number_format($stats['last7'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:24px;">
        <div class="card" style="margin:0;">
            <div class="card-head"><h2>Por dispositivo</h2></div>
            <div class="card-body">
                @if (empty($byDevice))
                    <p class="text-muted mt-0 mb-0">Nenhum scan ainda.</p>
                @else
                    <div class="table-wrap">
                        <table class="adm-table">
                            <thead>
                                <tr>
                                    <th>Dispositivo</th>
                                    <th class="text-right">Scans</th>
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
        foreach ($byDay as $day => $count) {
            $byDayLabels[] = \Carbon\Carbon::parse($day)->format('d/m');
            $byDayValues[] = (int) $count;
        }
    @endphp
    <div class="card" style="margin-bottom:24px;">
        <div class="card-head"><h2>Scans por dia (últimos 30 dias)</h2></div>
        <div class="card-body">
            <div class="campaign-day-chart-wrap">
                <canvas
                    id="campaignScansByDayChart"
                    aria-label="Gráfico de scans por dia nos últimos 30 dias"
                    role="img"
                    height="110"
                ></canvas>
            </div>
        </div>
    </div>
    <script type="application/json" id="campaignScansByDayData">@json(['labels' => $byDayLabels, 'values' => $byDayValues])</script>

    <div class="card">
        <div class="card-head"><h2>Visitas recentes</h2></div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Quando</th>
                            <th>Dispositivo</th>
                            <th>Navegador</th>
                            <th>Plataforma</th>
                            <th>Idioma</th>
                            <th>País</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recent as $visit)
                            <tr>
                                <td>{{ $visit->visited_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $visit->device_type ?? '—' }}</td>
                                <td>{{ $visit->browser ?? '—' }}</td>
                                <td>{{ $visit->platform ?? '—' }}</td>
                                <td>{{ $visit->accept_language ? \Illuminate\Support\Str::limit($visit->accept_language, 24) : '—' }}</td>
                                <td>{{ $visit->country_code ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">Nenhuma visita registrada ainda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .campaign-day-chart-wrap {
        position: relative;
        width: 100%;
        min-height: 280px;
    }
    .campaign-day-chart-wrap canvas {
        width: 100% !important;
        max-height: 320px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function () {
    var btn = document.getElementById('copyTrackingUrl');
    var el = document.getElementById('campaignTrackingUrl');
    if (btn && el) {
        btn.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(el.textContent.trim());
                btn.innerHTML = '<i class="bi bi-check2"></i> Copiado';
                setTimeout(function () {
                    btn.innerHTML = '<i class="bi bi-clipboard"></i> Copiar';
                }, 1600);
            } catch (e) {
                btn.innerHTML = '<i class="bi bi-x"></i> Falhou';
            }
        });
    }

    var canvas = document.getElementById('campaignScansByDayChart');
    var dataEl = document.getElementById('campaignScansByDayData');
    if (!canvas || !dataEl || typeof Chart === 'undefined') return;

    var payload;
    try {
        payload = JSON.parse(dataEl.textContent || '{}');
    } catch (e) {
        return;
    }

    var labels = payload.labels || [];
    var values = payload.values || [];
    var ctx = canvas.getContext('2d');
    var fillCache = { top: null, bottom: null, gradient: null };

    function areaFill(context) {
        var chart = context.chart;
        var area = chart.chartArea;
        if (!area) return 'rgba(66, 153, 225, 0.2)';
        if (fillCache.gradient && fillCache.top === area.top && fillCache.bottom === area.bottom) {
            return fillCache.gradient;
        }
        var gradient = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
        gradient.addColorStop(0, 'rgba(66, 153, 225, 0.38)');
        gradient.addColorStop(1, 'rgba(66, 153, 225, 0.02)');
        fillCache = { top: area.top, bottom: area.bottom, gradient: gradient };
        return gradient;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Scans',
                data: values,
                borderColor: '#4299e1',
                backgroundColor: areaFill,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#4299e1',
                pointBorderColor: '#4299e1',
                pointBorderWidth: 0,
                pointHitRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f8fafc',
                    bodyColor: '#e2e8f0',
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        title: function (items) {
                            return items[0] ? items[0].label : '';
                        },
                        label: function (item) {
                            var n = item.parsed.y || 0;
                            return n + (n === 1 ? ' scan' : ' scans');
                        }
                    }
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
