@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-testemunhos'; @endphp
@section('title', 'Testemunhos')
@section('heading', 'Meus testemunhos')

@section('content')
    @if ($disponiveis->isNotEmpty())
        <div class="card" style="margin-bottom:20px;">
            <div class="card-head"><h2>Novo testemunho</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('doutores.area.testemunhos.store') }}" class="form-grid">
                    @csrf
                    <div class="field">
                        <label for="plantao_id">Plantão <span class="req">*</span></label>
                        <select id="plantao_id" name="plantao_id" class="select" required>
                            @foreach ($disponiveis as $p)
                                <option value="{{ $p->id }}" {{ (string) old('plantao_id') === (string) $p->id ? 'selected' : '' }}>{{ $p->data?->format('d/m/Y') }} — {{ $p->hospital }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="texto">Seu testemunho <span class="req">*</span></label>
                        <textarea id="texto" name="texto" rows="5" class="input" required minlength="10" maxlength="5000" placeholder="Conte como foi a experiência…">{{ old('texto') }}</textarea>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-send"></i> Enviar</button></div>
                </form>
            </div>
        </div>
    @endif

    @forelse ($testemunhos as $t)
        <div class="card" style="margin-bottom:16px;">
            <div class="card-head">
                <h2>{{ $t->plantao?->hospital }} <span class="text-muted" style="font-weight:400;">· {{ $t->plantao?->data?->format('d/m/Y') }}</span></h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('doutores.area.testemunhos.update', $t) }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field">
                        <textarea name="texto" rows="4" class="input" required minlength="10" maxlength="5000">{{ old('texto', $t->texto) }}</textarea>
                    </div>
                    <div class="form-actions" style="border-top:0;padding-top:0;">
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-check-lg"></i> Salvar alterações</button>
                        <span class="muted-sm" style="align-self:center;">Atualizado em {{ $t->updated_at?->format('d/m/Y H:i') }}</span>
                    </div>
                </form>
            </div>
        </div>
    @empty
        @if ($disponiveis->isEmpty())
            <div class="card"><div class="empty-state"><i class="bi bi-chat-heart"></i>Quando você participar de um plantão, poderá registrar seu testemunho aqui.</div></div>
        @endif
    @endforelse
@endsection
