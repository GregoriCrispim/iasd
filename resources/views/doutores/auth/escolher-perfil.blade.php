@extends('doutores.layouts.auth')

@section('title', 'Escolher perfil')
@section('brand-subtitle', 'Escolha como deseja entrar')

@section('content')
    @php
        $labels = ['administrador' => 'Administrador', 'coordenador' => 'Coordenador de polo', 'voluntario' => 'Voluntário'];
    @endphp
    <form method="POST" action="{{ route('doutores.perfil.trocar') }}" class="dd-auth-form">
        @csrf
        <div class="field">
            <label for="perfil">Perfil</label>
            <select name="perfil" id="perfil" class="select" required>
                @foreach ($perfis as $p)
                    <option value="{{ $p }}" {{ $ativo === $p ? 'selected' : '' }}>{{ $labels[$p] ?? $p }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="dd-btn-brand dd-btn-block">Continuar</button>
    </form>
@endsection
