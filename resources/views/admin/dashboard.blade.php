@extends('admin.layout')

@php $activeNav = 'dashboard'; @endphp
@section('title', 'Início')
@section('heading', 'Início')

@section('content')
    <p class="text-muted mt-0">Bem-vindo(a), <strong>{{ auth('admin')->user()->name }}</strong>.</p>

    <div class="stat-grid">
        @if ($isSuper ?? false)
            <div class="stat">
                <i class="bi bi-file-earmark-text stat-icon"></i>
                <div class="stat-label">Páginas</div>
                <div class="stat-value">{{ $stats['pages'] ?? 0 }}</div>
            </div>
            <div class="stat">
                <i class="bi bi-grid-1x2 stat-icon"></i>
                <div class="stat-label">Blocos</div>
                <div class="stat-value">{{ $stats['blocks'] ?? 0 }}</div>
            </div>
        @endif
        @if ($isCmsUser ?? false)
            <div class="stat">
                <i class="bi bi-pencil-square stat-icon"></i>
                <div class="stat-label">Minhas revisões</div>
                <div class="stat-value">{{ $stats['my_revisions'] ?? 0 }}</div>
            </div>
        @endif
        @if (($isSuper ?? false) || ($isManager ?? false))
            <div class="stat">
                <i class="bi bi-inbox stat-icon"></i>
                <div class="stat-label">Aguardando aprovação</div>
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
            </div>
        @endif
        @if ($canGaleria ?? false)
            <div class="stat">
                <i class="bi bi-folder2-open stat-icon"></i>
                <div class="stat-label">Álbuns</div>
                <div class="stat-value">{{ $stats['albums'] ?? 0 }}</div>
            </div>
            <div class="stat">
                <i class="bi bi-images stat-icon"></i>
                <div class="stat-label">Fotos</div>
                <div class="stat-value">{{ $stats['photos'] ?? 0 }}</div>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-head"><h2>Atalhos</h2></div>
        <div class="card-body">
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                @if ($canGaleria ?? false)
                    <a href="{{ route('admin.galeria.index', ['novo' => 1]) }}" class="btn"><i class="bi bi-plus-lg"></i> Novo álbum</a>
                    <a href="{{ route('admin.galeria.index') }}" class="btn btn-secondary"><i class="bi bi-images"></i> Galeria</a>
                @endif
                @if ($isCmsUser ?? false)
                    <a href="{{ route('admin.revisions.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Nova revisão</a>
                    <a href="{{ route('admin.revisions.index') }}" class="btn btn-secondary"><i class="bi bi-pencil-square"></i> Ver revisões</a>
                @endif
                @if (($isSuper ?? false) || ($isManager ?? false))
                    <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary"><i class="bi bi-inbox"></i> Aprovações</a>
                @endif
            </div>
        </div>
    </div>
@endsection
