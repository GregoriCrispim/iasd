@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-avisos'; @endphp
@section('title', 'Novo aviso')
@section('heading', 'Novo aviso')

@section('actions')
    <a href="{{ route('doutores.avisos.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.avisos.store') }}" class="form-grid">
                @csrf
                <div class="form-row">
                    <div class="field">
                        <label for="plantao_id">Plantão <span class="req">*</span></label>
                        <select id="plantao_id" name="plantao_id" class="select" required>
                            <option value="">Selecione…</option>
                            @foreach ($plantoes as $p)
                                <option value="{{ $p->id }}" {{ (string) old('plantao_id', $plantaoId) === (string) $p->id ? 'selected' : '' }}>{{ $p->data?->format('d/m/Y') }} — {{ $p->hospital }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="equipe_numero">Equipe (opcional)</label>
                        <input type="number" min="1" id="equipe_numero" name="equipe_numero" class="input" value="{{ old('equipe_numero', $equipeNumero) }}" placeholder="Vazio = todos os inscritos">
                    </div>
                </div>
                <div class="field">
                    <label for="titulo">Título <span class="req">*</span></label>
                    <input type="text" id="titulo" name="titulo" class="input" value="{{ old('titulo') }}" required maxlength="255">
                </div>
                <div class="field">
                    <label for="conteudo">Mensagem</label>
                    <textarea id="conteudo" name="conteudo" rows="6" class="input" maxlength="5000">{{ old('conteudo') }}</textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-send"></i> Publicar aviso</button>
                    <a href="{{ route('doutores.avisos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
