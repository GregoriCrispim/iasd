@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-dominios'; @endphp
@section('title', 'Domínios')
@section('heading', 'Domínios (listas de opções)')

@section('content')
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
        @foreach ($categorias as $key => $label)
            <a href="{{ route('doutores.dominios.index', ['categoria' => $key]) }}" class="btn {{ $categoria === $key ? '' : 'btn-secondary' }} btn-sm">
                {{ $label }} <span class="badge badge-gray">{{ $contagens[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div class="card-head"><h2>Adicionar em “{{ $categorias[$categoria] }}”</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.dominios.store') }}" class="filters" style="padding:0;border:0;">
                @csrf
                <input type="hidden" name="categoria" value="{{ $categoria }}">
                <div class="field" style="flex:1;min-width:260px;">
                    <label for="descricao">Descrição</label>
                    <input type="text" id="descricao" name="descricao" class="input" required maxlength="255" value="{{ old('descricao') }}">
                </div>
                <button type="submit" class="btn"><i class="bi bi-plus-lg"></i> Adicionar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Descrição</th><th>Ativo</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($itens as $item)
                        <tr>
                            <td colspan="3">
                                <form method="POST" action="{{ route('doutores.dominios.update', $item) }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                                    @csrf @method('PUT')
                                    <input type="text" name="descricao" class="input" value="{{ $item->descricao }}" required maxlength="255" style="flex:1;min-width:220px;">
                                    <label class="checkbox-field"><input type="checkbox" name="ativo" value="1" {{ $item->ativo ? 'checked' : '' }}> Ativo</label>
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Salvar"><i class="bi bi-check-lg"></i> Salvar</button>
                                    <button type="submit" form="del-dominio-{{ $item->id }}" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                </form>
                                <form method="POST" action="{{ route('doutores.dominios.destroy', $item) }}" id="del-dominio-{{ $item->id }}"
                                      onsubmit="return admConfirm({{ json_encode('Remover “'.$item->descricao.'”?') }}, this, { title: 'Remover item' });">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty-state"><i class="bi bi-list-check"></i>Nenhum item nesta categoria.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
