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

    <div class="card" style="margin-bottom:24px;">
        <div class="card-head"><h2>Scans por dia (30 dias)</h2></div>
        <div class="card-body">
            <div class="table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th style="width:50%;">Volume</th>
                            <th class="text-right">Scans</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (array_reverse($byDay, true) as $day => $count)
                            @php $pct = $count ? max(4, (int) round(($count / $maxDay) * 100)) : 0; @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</td>
                                <td>
                                    <div style="background:#e2e8f0;border-radius:999px;height:8px;overflow:hidden;">
                                        <div style="width:{{ $pct }}%;height:100%;background:var(--adm-primary);"></div>
                                    </div>
                                </td>
                                <td class="text-right">{{ number_format($count, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('copyTrackingUrl');
    var el = document.getElementById('campaignTrackingUrl');
    if (!btn || !el) return;
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
})();
</script>
@endpush
