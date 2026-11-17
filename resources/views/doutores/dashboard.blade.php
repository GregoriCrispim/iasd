@extends('doutores.layouts.secured')

@php
    $activeNav = 'doutores-dashboard';
    $palette = ['#2563eb', '#16a34a', '#d97706', '#7e22ce'];
@endphp
@section('title', 'Indicadores')
@section('heading', 'Indicadores — últimos 12 meses')

@push('styles')
<style>
    .dbar { display:flex; align-items:flex-end; gap:6px; height:150px; padding-top:8px; }
    .dbar-col { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; min-width:0; }
    .dbar-col .bar { width:100%; max-width:34px; border-radius:6px 6px 0 0; min-height:2px; }
    .dbar-col .val { font-size:11px; color:#475569; margin-bottom:3px; }
    .dbar-labels { display:flex; gap:6px; margin-top:6px; }
    .dbar-labels span { flex:1; text-align:center; font-size:10.5px; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
</style>
@endpush

@section('content')
    <div class="stat-grid">
        <div class="stat"><i class="bi bi-door-open stat-icon"></i><div class="stat-label">Quartos visitados</div><div class="stat-value">{{ number_format($resultados['quartos'], 0, ',', '.') }}</div></div>
        <div class="stat"><i class="bi bi-heart stat-icon"></i><div class="stat-label">Visitas realizadas</div><div class="stat-value">{{ number_format($resultados['visitas'], 0, ',', '.') }}</div></div>
        <div class="stat"><i class="bi bi-book stat-icon"></i><div class="stat-label">Livros distribuídos</div><div class="stat-value">{{ number_format($resultados['livros'], 0, ',', '.') }}</div></div>
    </div>

    <div class="form-row" style="margin-bottom:18px;">
        @foreach ($series as $titulo => $valores)
            @php $max = max(1, max($valores)); $color = $palette[$loop->index % count($palette)]; @endphp
            <div class="card" style="margin-bottom:0;">
                <div class="card-head"><h2>{{ $titulo }}</h2><div class="adm-actions"><span class="badge badge-gray">Total: {{ array_sum($valores) }}</span></div></div>
                <div class="card-body">
                    <div class="dbar" role="img" aria-label="{{ $titulo }} por mês">
                        @foreach ($valores as $i => $valor)
                            <div class="dbar-col" title="{{ $labels[$i] }}: {{ $valor }}">
                                <span class="val">{{ $valor ?: '' }}</span>
                                <div class="bar" style="height:{{ max(2, round(($valor / $max) * 110)) }}px;background:{{ $color }};opacity:{{ $valor ? 1 : .25 }};"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="dbar-labels">
                        @foreach ($labels as $label)<span>{{ $label }}</span>@endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
