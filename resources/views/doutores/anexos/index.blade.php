@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-anexos'; @endphp
@section('title', 'Documentos')
@section('heading', 'Documentos')

@section('content')
    <div class="card" style="margin-bottom:20px;">
        <div class="card-head"><h2>Enviar documento</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.anexos.store') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                <div class="form-row">
                    <div class="field">
                        <label for="tipo">Tipo <span class="req">*</span></label>
                        <select id="tipo" name="tipo" class="select" required>
                            @foreach (\App\Models\Doutores\Anexo::TIPOS as $k => $l)
                                <option value="{{ $k }}" {{ old('tipo') === $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="arquivo">Arquivo <span class="req">*</span></label>
                        <input type="file" id="arquivo" name="arquivo" class="input" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                        <span class="hint">PDF, JPG, PNG ou WEBP — até 5 MB.</span>
                    </div>
                </div>
                <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-upload"></i> Enviar</button></div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-head"><h2>Meus documentos</h2></div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Tipo</th><th>Arquivo</th><th>Tamanho</th><th>Enviado em</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($meus as $a)
                        <tr>
                            <td>{{ \App\Models\Doutores\Anexo::TIPOS[$a->tipo] ?? $a->tipo }}</td>
                            <td><a href="{{ route('doutores.anexos.download', $a) }}">{{ $a->nome_arquivo }}</a></td>
                            <td>{{ number_format($a->tamanho / 1024, 0, ',', '.') }} KB</td>
                            <td>{{ $a->created_at?->format('d/m/Y H:i') }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $a->status])</td>
                            <td class="col-actions">
                                @if ($a->status !== 'aprovado')
                                    <form method="POST" action="{{ route('doutores.anexos.destroy', $a) }}"
                                          onsubmit="return admConfirm({{ json_encode('Remover '.$a->nome_arquivo.'?') }}, this, { title: 'Remover documento' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-paperclip"></i>Você ainda não enviou documentos.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (auth('doutores')->user() && \App\Support\DoutoresAuth::getActivePerfil(request()) !== 'voluntario')
        <div class="card">
            <div class="card-head">
                <h2>Documentos para revisão</h2>
                <div class="adm-actions">
                    <form method="GET" style="display:flex;gap:8px;">
                        <select name="status" class="select" onchange="this.form.submit()">
                            @foreach (\App\Models\Doutores\Anexo::STATUSES as $k => $l)
                                <option value="{{ $k }}" {{ request('status', 'enviado') === $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="table-wrap">
                <table class="adm-table">
                    <thead><tr><th>Voluntário</th><th>Tipo</th><th>Arquivo</th><th>Enviado em</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                    <tbody>
                        @forelse ($revisao as $a)
                            <tr>
                                <td>
                                    <strong>{{ $a->user?->name }}</strong>
                                    @if ($a->user?->doutoresProfile)
                                        <br><a class="muted-sm" href="{{ route('doutores.voluntarios.show', $a->user->doutoresProfile) }}">ver perfil</a>
                                    @endif
                                </td>
                                <td>{{ \App\Models\Doutores\Anexo::TIPOS[$a->tipo] ?? $a->tipo }}</td>
                                <td><a href="{{ route('doutores.anexos.download', $a) }}">{{ $a->nome_arquivo }}</a></td>
                                <td>{{ $a->created_at?->format('d/m/Y H:i') }}</td>
                                <td>@include('doutores.partials.badge', ['status' => $a->status])</td>
                                <td class="col-actions">
                                    <form method="POST" action="{{ route('doutores.anexos.revisar', $a) }}" class="row-actions">
                                        @csrf @method('PUT')
                                        <button name="status" value="aprovado" class="btn btn-success btn-sm" title="Aprovar"><i class="bi bi-check-lg"></i></button>
                                        <button name="status" value="rejeitado" class="btn btn-danger btn-sm" title="Rejeitar"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-check2-circle"></i>Nada para revisar.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
