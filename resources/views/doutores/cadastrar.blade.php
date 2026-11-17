@extends('doutores.layouts.portal')

@section('title', 'Seja voluntário')
@section('meta-description', 'Cadastro de voluntários do projeto Doutores da Esperança da IASD Central de Brasília.')

@section('content')
<div class="dd-wrap">
    <div class="dd-card">
        <h1>Seja um Doutor da Esperança</h1>
        <p class="dd-sub">Preencha o cadastro para fazer parte do voluntariado em hospitais. A coordenação irá analisar suas informações e liberar seu acesso ao painel.</p>

        @if (session('cadastro_ok'))
            <div class="dd-alert ok" role="status">{{ session('cadastro_ok') }}</div>
        @endif

        @if (session('error'))
            <div class="dd-alert err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="dd-alert err" role="alert">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @unless (session('cadastro_ok'))
        <form method="POST" action="{{ route('doutores.cadastrar.store') }}" novalidate>
            @csrf

            <div class="dd-section">Dados de acesso</div>
            <div class="dd-grid">
                <div class="dd-field full">
                    <label for="name">Nome completo<span class="req">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name">
                </div>
                <div class="dd-field">
                    <label for="email">E-mail<span class="req">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="190" autocomplete="email">
                </div>
                <div class="dd-field">
                    <label for="telefone">Telefone / WhatsApp<span class="req">*</span></label>
                    <input type="tel" id="telefone" name="telefone" value="{{ old('telefone') }}" required maxlength="20" autocomplete="tel">
                </div>
                <div class="dd-field">
                    <label for="password">Senha<span class="req">*</span></label>
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Mín. 8 caracteres, letras e números">
                </div>
                <div class="dd-field">
                    <label for="password_confirmation">Confirmar senha<span class="req">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password">
                </div>
            </div>

            <div class="dd-section">Dados pessoais</div>
            <div class="dd-grid">
                <div class="dd-field">
                    <label for="cpf">CPF<span class="req">*</span></label>
                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" required maxlength="14" inputmode="numeric" placeholder="000.000.000-00">
                </div>
                <div class="dd-field">
                    <label for="genero">Gênero</label>
                    <div class="dd-select" data-dd-select>
                        <select id="genero" name="genero" class="dd-select-native" data-placeholder="Selecione">
                            <option value="">Selecione</option>
                            @foreach (['feminino' => 'Feminino', 'masculino' => 'Masculino', 'outro' => 'Outro'] as $k => $l)
                                <option value="{{ $k }}" {{ old('genero') === $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dd-field">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="{{ old('cep') }}" maxlength="9" inputmode="numeric">
                </div>
                <div class="dd-field">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}" maxlength="255">
                </div>
                <div class="dd-field">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}" maxlength="255">
                </div>
                <div class="dd-field">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}" maxlength="255">
                </div>
            </div>

            <div class="dd-section">Voluntariado</div>
            <div class="dd-grid">
                <div class="dd-field">
                    <label for="hospital_preferido">Hospital de preferência</label>
                    <div class="dd-select" data-dd-select>
                        <select id="hospital_preferido" name="hospital_preferido" class="dd-select-native" data-placeholder="Selecione">
                            <option value="">Selecione</option>
                            @foreach ($hospitais as $h)
                                <option value="{{ $h }}" {{ old('hospital_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dd-field">
                    <label for="igreja">Igreja</label>
                    @if (count($igrejas))
                        <div class="dd-select" data-dd-select>
                            <select id="igreja" name="igreja" class="dd-select-native" data-placeholder="Selecione">
                                <option value="">Selecione</option>
                                @foreach ($igrejas as $h)
                                    <option value="{{ $h }}" {{ old('igreja') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="text" id="igreja" name="igreja" value="{{ old('igreja') }}" maxlength="255">
                    @endif
                </div>
                <div class="dd-field">
                    <label for="dia_preferido">Dia preferido</label>
                    @if (count($dias))
                        <div class="dd-select" data-dd-select>
                            <select id="dia_preferido" name="dia_preferido" class="dd-select-native" data-placeholder="Selecione">
                                <option value="">Selecione</option>
                                @foreach ($dias as $h)
                                    <option value="{{ $h }}" {{ old('dia_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="text" id="dia_preferido" name="dia_preferido" value="{{ old('dia_preferido') }}" maxlength="255">
                    @endif
                </div>
                <div class="dd-field">
                    <label for="horario_preferido">Turno preferido</label>
                    @if (count($turnos))
                        <div class="dd-select" data-dd-select>
                            <select id="horario_preferido" name="horario_preferido" class="dd-select-native" data-placeholder="Selecione">
                                <option value="">Selecione</option>
                                @foreach ($turnos as $h)
                                    <option value="{{ $h }}" {{ old('horario_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="text" id="horario_preferido" name="horario_preferido" value="{{ old('horario_preferido') }}" maxlength="255">
                    @endif
                </div>
                <div class="dd-field full">
                    <label for="cargo">Como você gostaria de ajudar?</label>
                    @if (count($funcoes))
                        <div class="dd-select" data-dd-select>
                            <select id="cargo" name="cargo" class="dd-select-native" data-placeholder="Selecione">
                                <option value="">Selecione</option>
                                @foreach ($funcoes as $h)
                                    <option value="{{ $h }}" {{ old('cargo') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="text" id="cargo" name="cargo" value="{{ old('cargo') }}" maxlength="255">
                    @endif
                </div>
            </div>

            <label class="dd-check">
                <input type="checkbox" name="accept_terms" value="1" {{ old('accept_terms') ? 'checked' : '' }} required>
                <span>Li e aceito o termo de voluntariado e autorizo o uso dos meus dados para a organização do projeto, conforme a LGPD.</span>
            </label>

            <button type="submit" class="dd-btn-brand dd-btn-block" style="margin-top:1.35rem;">Enviar cadastro</button>
        </form>
        @endunless

        <div class="dd-foot">
            Já é voluntário? <a href="{{ route('doutores.login') }}">Entrar no painel</a> ·
            <a href="{{ route('doutores-da-esperanca') }}">Conheça o projeto</a>
        </div>
    </div>
</div>
@endsection
