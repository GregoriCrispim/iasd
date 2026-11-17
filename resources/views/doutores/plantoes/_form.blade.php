@php
    $dtLocal = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d\TH:i') : '';
    $editing = $plantao->exists;
@endphp
<form method="POST" action="{{ $editing ? route('doutores.plantoes.update', $plantao) : route('doutores.plantoes.store') }}" class="form-grid">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="form-row">
        <div class="field">
            <label for="hospital">Hospital <span class="req">*</span></label>
            <select id="hospital" name="hospital" class="select" required onchange="admPlantaoHospital(this)">
                <option value="">Selecione…</option>
                @foreach ($hospitais as $h)
                    <option value="{{ $h }}" data-endereco="{{ $hospitaisInfo->get($h)?->endereco ?? '' }}" {{ old('hospital', $plantao->hospital) === $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
            @if (count($hospitais) === 0)
                <span class="hint">Nenhum hospital disponível para o seu polo.</span>
            @endif
        </div>
        <div class="field">
            <label for="endereco">Endereço</label>
            <input type="text" id="endereco" name="endereco" class="input" value="{{ old('endereco', $plantao->endereco) }}" maxlength="255" placeholder="Preenchido pelo hospital quando vazio">
        </div>
    </div>

    <div class="form-row">
        <div class="field">
            <label for="data">Data <span class="req">*</span></label>
            <input type="date" id="data" name="data" class="input" value="{{ old('data', $plantao->data?->format('Y-m-d')) }}" required>
        </div>
        <div class="form-row" style="gap:12px;">
            <div class="field">
                <label for="hora_inicio">Início</label>
                <input type="time" id="hora_inicio" name="hora_inicio" class="input" value="{{ old('hora_inicio', $plantao->hora_inicio) }}">
            </div>
            <div class="field">
                <label for="hora_fim">Fim</label>
                <input type="time" id="hora_fim" name="hora_fim" class="input" value="{{ old('hora_fim', $plantao->hora_fim) }}">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="field">
            <label for="tipo">Tipo <span class="req">*</span></label>
            <select id="tipo" name="tipo" class="select" required>
                @foreach (\App\Models\Doutores\Plantao::TIPOS as $k => $l)
                    <option value="{{ $k }}" {{ old('tipo', $plantao->tipo) === $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="status">Status <span class="req">*</span></label>
            <select id="status" name="status" class="select" required>
                @foreach (\App\Models\Doutores\Plantao::STATUSES as $k => $l)
                    <option value="{{ $k }}" {{ old('status', $plantao->status) === $k ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="field">
            <label for="inscricao_inicio">Inscrições a partir de</label>
            <input type="datetime-local" id="inscricao_inicio" name="inscricao_inicio" class="input" value="{{ old('inscricao_inicio', $dtLocal($plantao->inscricao_inicio)) }}">
        </div>
        <div class="field">
            <label for="inscricao_fim">Inscrições até</label>
            <input type="datetime-local" id="inscricao_fim" name="inscricao_fim" class="input" value="{{ old('inscricao_fim', $dtLocal($plantao->inscricao_fim)) }}">
        </div>
    </div>

    <div class="field">
        <label for="observacoes">Observações</label>
        <textarea id="observacoes" name="observacoes" rows="4" class="input" maxlength="5000">{{ old('observacoes', $plantao->observacoes) }}</textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
        <a href="{{ $editing ? route('doutores.plantoes.show', $plantao) : route('doutores.plantoes.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@push('scripts')
<script>
    function admPlantaoHospital(select) {
        var opt = select.options[select.selectedIndex];
        var end = document.getElementById('endereco');
        if (opt && end && !end.value && opt.dataset.endereco) end.value = opt.dataset.endereco;
    }
</script>
@endpush
