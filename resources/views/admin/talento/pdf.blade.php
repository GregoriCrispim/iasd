<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Meu Talento — Relatório de Inscritos</title>
    <style>
        @page {
            margin: 18mm 14mm 20mm 14mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .header td {
            vertical-align: middle;
            padding: 0;
        }

        .brand-bar {
            background: #0b3d5c;
            color: #fff;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 2px 0;
            letter-spacing: -0.02em;
        }

        .brand-sub {
            font-size: 11px;
            color: #dbeafe;
            margin: 0;
        }

        .logo {
            width: 52px;
            height: auto;
            margin-right: 12px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 18px;
        }

        .meta td {
            width: 25%;
            padding: 10px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .meta .label {
            display: block;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin-bottom: 3px;
        }

        .meta .value {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #0b3d5c;
        }

        .filters {
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #9a3412;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 10px;
        }

        .ministry {
            margin-bottom: 16px;
            page-break-inside: avoid;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .ministry-head {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        .ministry-head td {
            padding: 10px 12px;
            vertical-align: middle;
        }

        .ministry-name {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
        }

        .people {
            width: 100%;
            border-collapse: collapse;
        }

        .people th {
            background: #f8fafc;
            color: #64748b;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .people td {
            padding: 7px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        .people tr:nth-child(even) td {
            background: #f8fafc;
        }

        .people tr:last-child td {
            border-bottom: 0;
        }

        .name {
            font-weight: bold;
            color: #0f172a;
        }

        .muted {
            color: #64748b;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            white-space: nowrap;
        }

        .badge-lideranca {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-equipe {
            background: #dbeafe;
            color: #1e40af;
        }

        .empty {
            text-align: center;
            padding: 40px 16px;
            color: #64748b;
        }

        .footer {
            position: fixed;
            bottom: -12mm;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $hasFilters = ($filters['q'] ?? '') !== ''
            || ($filters['ministry'] ?? '') !== ''
            || ($filters['modality'] ?? '') !== '';

        $ministryLabel = '';
        if (($filters['ministry'] ?? '') !== '') {
            $entry = $ministries[$filters['ministry']] ?? null;
            $ministryLabel = is_array($entry) ? ($entry['name'] ?? $filters['ministry']) : ($entry ?? $filters['ministry']);
        }

        $modalityLabel = match ($filters['modality'] ?? '') {
            'lideranca' => 'Liderança',
            'equipe' => 'Equipe',
            default => '',
        };
    @endphp

    <table class="header">
        <tr>
            <td style="width: 64px;">
                @if ($logoDataUri)
                    <img class="logo" src="{{ $logoDataUri }}" alt="IASD">
                @endif
            </td>
            <td>
                <div class="brand-bar">
                    <p class="brand-title">Meu Talento, Meu Ministério</p>
                    <p class="brand-sub">Relatório de inscritos por ministério · IASD Central Brasília</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td>
                <span class="label">Inscritos</span>
                <span class="value">{{ number_format($stats['volunteers'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Liderança</span>
                <span class="value">{{ number_format($stats['lideranca'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Equipe</span>
                <span class="value">{{ number_format($stats['equipe'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Ministérios</span>
                <span class="value">{{ number_format($stats['ministries_with_candidates'], 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    @if ($hasFilters)
        <div class="filters">
            <strong>Filtros aplicados:</strong>
            @if (($filters['q'] ?? '') !== '')
                busca “{{ $filters['q'] }}”
            @endif
            @if ($ministryLabel !== '')
                · ministério {{ $ministryLabel }}
            @endif
            @if ($modalityLabel !== '')
                · modalidade {{ $modalityLabel }}
            @endif
        </div>
    @endif

    <p class="muted" style="margin: 0 0 14px;">
        Gerado em {{ $generatedAt->format('d/m/Y \à\s H:i') }}
        · Organização por ministério (Liderança primeiro, depois Equipe)
    </p>

    @forelse ($byMinistry as $block)
        <div class="ministry">
            <table class="ministry-head" style="background: {{ $block['color'] }};">
                <tr>
                    <td>
                        <p class="ministry-name">{{ $block['label'] }}</p>
                    </td>
                </tr>
            </table>

            <table class="people">
                <thead>
                    <tr>
                        <th style="width: 28%;">Candidato</th>
                        <th style="width: 18%;">WhatsApp</th>
                        <th style="width: 28%;">E-mail</th>
                        <th style="width: 14%;">Modalidade</th>
                        <th style="width: 12%;">Inscrição</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($block['candidates'] as $candidate)
                        <tr>
                            <td class="name">{{ $candidate['name'] }}</td>
                            <td>{{ $candidate['phone'] }}</td>
                            <td class="muted">{{ $candidate['email'] }}</td>
                            <td>
                                <span class="badge {{ $candidate['modality'] === 'lideranca' ? 'badge-lideranca' : 'badge-equipe' }}">
                                    {{ $candidate['modality_label'] }}
                                </span>
                            </td>
                            <td class="muted">{{ $candidate['created_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="empty">
            Nenhuma inscrição encontrada para gerar o relatório.
        </div>
    @endforelse

    <div class="footer">
        IASD Central Brasília · Meu Talento, Meu Ministério · Gerado em {{ $generatedAt->format('d/m/Y H:i') }}
    </div>
</body>
</html>
