@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-perfil'; @endphp
@section('title', 'Meu perfil')
@section('heading', 'Meu perfil')

@section('content')
    @php
        $val = fn (string $field, $default = null) => old($field, $profile->{$field} ?? $default);
        $opt = function (array $list, ?string $current) {
            $list = collect($list);
            if ($current !== null && $current !== '' && ! $list->contains($current)) {
                $list->push($current);
            }
            return $list;
        };
    @endphp

    <div class="card">
        <div class="card-head">
            <h2>Dados pessoais</h2>
            <div class="adm-actions">@include('doutores.partials.badge', ['status' => $profile->status])</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.area.perfil.update') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="field">
                        <label for="name">Nome <span class="req">*</span></label>
                        <input type="text" id="name" name="name" class="input" value="{{ old('name', $targetUser->name) }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label>E-mail</label>
                        <input type="text" class="input" value="{{ $targetUser->email }}" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>CPF</label>
                        <input type="text" class="input" value="{{ $profile->cpf ?? '—' }}" disabled>
                    </div>
                    <div class="field">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" class="input" value="{{ $val('telefone') }}" maxlength="20">
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="genero">Gênero</label>
                        <select id="genero" name="genero" class="select">
                            <option value="">—</option>
                            @foreach (['feminino' => 'Feminino', 'masculino' => 'Masculino', 'outro' => 'Outro'] as $k => $l)
                                <option value="{{ $k }}" {{ $val('genero') === $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="foto">Foto</label>
                        <input type="file" id="foto" name="foto" class="input" accept="image/*">
                        @if ($profile->foto_path)
                            <span class="hint">Foto atual: <a href="{{ \App\Support\DoutoresStorage::publicUrl($profile->foto_path) }}" target="_blank" rel="noopener">ver</a></span>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="input" value="{{ $val('cep') }}" maxlength="9">
                    </div>
                    <div class="field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="input" value="{{ $val('cidade') }}" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="input" value="{{ $val('endereco') }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" class="input" value="{{ $val('bairro') }}" maxlength="255">
                    </div>
                </div>

                <h3 style="margin:8px 0 0;font-size:15px;">Preferências de voluntariado</h3>
                <div class="form-row">
                    <div class="field">
                        <label for="hospital_preferido">Hospital preferido</label>
                        <select id="hospital_preferido" name="hospital_preferido" class="select">
                            <option value="">—</option>
                            @foreach ($opt($hospitais->all(), $profile->hospital_preferido) as $h)
                                <option value="{{ $h }}" {{ $val('hospital_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="igreja">Igreja</label>
                        <select id="igreja" name="igreja" class="select">
                            <option value="">—</option>
                            @foreach ($opt($igrejas, $profile->igreja) as $h)
                                <option value="{{ $h }}" {{ $val('igreja') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="dia_preferido">Dia preferido</label>
                        <select id="dia_preferido" name="dia_preferido" class="select">
                            <option value="">—</option>
                            @foreach ($opt($dias, $profile->dia_preferido) as $h)
                                <option value="{{ $h }}" {{ $val('dia_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="horario_preferido">Turno preferido</label>
                        <select id="horario_preferido" name="horario_preferido" class="select">
                            <option value="">—</option>
                            @foreach ($opt($turnos, $profile->horario_preferido) as $h)
                                <option value="{{ $h }}" {{ $val('horario_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="cargo">Função principal</label>
                    <select id="cargo" name="cargo" class="select">
                        <option value="">—</option>
                        @foreach ($opt($funcoes, $profile->cargo) as $h)
                            <option value="{{ $h }}" {{ $val('cargo') === $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
