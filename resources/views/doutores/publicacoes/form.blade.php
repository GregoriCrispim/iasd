@extends('doutores.layouts.secured')

@php
    $activeNav = 'doutores-publicacoes';
    $editing = $publicacao->exists;
@endphp
@section('title', $editing ? 'Editar publicação' : 'Nova publicação')
@section('heading', $editing ? 'Editar publicação' : 'Nova publicação')

@section('actions')
    <a href="{{ route('doutores.publicacoes.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('doutores.publicacoes.update', $publicacao) : route('doutores.publicacoes.store') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="form-row">
                    <div class="field">
                        <label for="titulo">Título <span class="req">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="input" value="{{ old('titulo', $publicacao->titulo) }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label for="status">Status <span class="req">*</span></label>
                        <select id="status" name="status" class="select" required>
                            <option value="publicado" {{ old('status', $publicacao->status) === 'publicado' ? 'selected' : '' }}>Publicado</option>
                            <option value="rascunho" {{ old('status', $publicacao->status) === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="conteudo">Conteúdo</label>
                    <textarea id="conteudo" name="conteudo" rows="10" class="input" maxlength="20000">{{ old('conteudo', $publicacao->conteudo) }}</textarea>
                </div>

                @if ($editing && count($publicacao->imagens ?? []))
                    <div class="field">
                        <label>Imagens atuais</label>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            @foreach ($publicacao->imagens as $img)
                                <label style="display:flex;flex-direction:column;gap:4px;align-items:center;font-size:12px;">
                                    <img src="{{ \App\Support\DoutoresStorage::publicUrl($img) }}" alt="" style="width:110px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--adm-border);">
                                    <span><input type="checkbox" name="remover_imagens[]" value="{{ $img }}"> remover</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="field">
                    <label for="imagens">Adicionar imagens</label>
                    <input type="file" id="imagens" name="imagens[]" class="input" accept="image/*" multiple>
                    <span class="hint">Até 10 imagens, 4 MB cada.</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('doutores.publicacoes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    @if ($editing)
        <div class="card">
            <div class="card-head"><h2>Comentários ({{ $publicacao->comentarios->count() }})</h2></div>
            <div class="table-wrap">
                <table class="adm-table">
                    <thead><tr><th>Autor</th><th>Mensagem</th><th>Status</th><th class="col-actions">Moderação</th></tr></thead>
                    <tbody>
                        @forelse ($publicacao->comentarios as $c)
                            <tr>
                                <td><strong>{{ $c->nome }}</strong><br><span class="muted-sm">{{ $c->email }}<br>{{ $c->created_at?->format('d/m/Y H:i') }}</span></td>
                                <td style="max-width:420px;white-space:pre-line;">{{ $c->mensagem }}</td>
                                <td>@include('doutores.partials.badge', ['status' => $c->status])</td>
                                <td class="col-actions">
                                    <div class="row-actions">
                                        <form method="POST" action="{{ route('doutores.publicacoes.comentarios.moderar', $c) }}" class="row-actions">
                                            @csrf @method('PUT')
                                            @if ($c->status !== 'aprovado')<button name="status" value="aprovado" class="btn btn-success btn-sm" title="Aprovar"><i class="bi bi-check-lg"></i></button>@endif
                                            @if ($c->status !== 'rejeitado')<button name="status" value="rejeitado" class="btn btn-warning btn-sm" title="Rejeitar"><i class="bi bi-slash-circle"></i></button>@endif
                                        </form>
                                        <form method="POST" action="{{ route('doutores.publicacoes.comentarios.destroy', $c) }}"
                                              onsubmit="return admConfirm('Remover este comentário?', this, { title: 'Remover comentário' });">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-chat"></i>Sem comentários.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
