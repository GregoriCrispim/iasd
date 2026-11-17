@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-termos'; @endphp
@section('title', 'Termos de voluntariado')
@section('heading', 'Termos de voluntariado')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 style="margin-top:0;">Termo de compromisso — Doutores da Esperança</h2>
            <ol style="line-height:1.7;">
                <li>Atuo de forma voluntária, sem qualquer remuneração, em visitas a pacientes e familiares em hospitais.</li>
                <li>Respeito as normas de cada hospital, os horários combinados e as orientações da coordenação do meu polo.</li>
                <li>Mantenho sigilo sobre informações de pacientes, familiares e equipe hospitalar, a que tiver acesso em razão do voluntariado.</li>
                <li>Não faço proselitismo forçado, respeitando a fé, a cultura e a vontade de cada pessoa visitada.</li>
                <li>Comunico com antecedência quando não puder comparecer a um plantão em que estiver inscrito.</li>
                <li>Concordo com o tratamento dos meus dados pessoais e documentos para fins de organização do voluntariado, conforme a LGPD.</li>
            </ol>

            @if ($profile->termos_aceitos_em)
                <div class="alert alert-success" style="margin-top:20px;">
                    <i class="bi bi-check-circle"></i>
                    <div>Termos aceitos em <strong>{{ $profile->termos_aceitos_em->format('d/m/Y H:i') }}</strong>.</div>
                </div>
            @else
                <form method="POST" action="{{ route('doutores.area.termos.aceitar') }}" class="form-grid" style="margin-top:20px;">
                    @csrf
                    <label class="checkbox-field">
                        <input type="checkbox" name="aceito" value="1" required>
                        <span>Li e aceito os termos de voluntariado.</span>
                    </label>
                    <div class="form-actions">
                        <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Aceitar termos</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
