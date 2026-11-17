@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-plantoes'; @endphp
@section('title', 'Editar plantão')
@section('heading', 'Editar plantão')

@section('actions')
    <a href="{{ route('doutores.plantoes.show', $plantao) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card"><div class="card-body">@include('doutores.plantoes._form')</div></div>
@endsection
