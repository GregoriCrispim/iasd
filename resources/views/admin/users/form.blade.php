@extends('admin.layout')

@php
    $activeNav = 'users';
    $editing = $user->exists;
    $isManagerAuth = auth('admin')->user()->isManager();
@endphp
@section('title', $editing ? 'Editar usuário' : 'Novo usuário')
@section('heading', $editing ? 'Editar usuário' : 'Novo usuário')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="form-row">
                    <div class="field">
                        <label>Nome <span class="req">*</span></label>
                        <input type="text" name="name" class="input" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label>E-mail <span class="req">*</span></label>
                        <input type="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>Senha @if (!$editing)<span class="req">*</span>@endif</label>
                        <input type="password" name="password" class="input" autocomplete="new-password" @if (!$editing) required @endif>
                        @if ($editing)<span class="hint">Deixe em branco para manter a senha atual.</span>@endif
                    </div>

                    @unless ($isManagerAuth)
                        <div class="field">
                            <label>Perfil <span class="req">*</span></label>
                            <select name="role" class="select" required>
                                @foreach ($roleOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $currentRole) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endunless
                </div>

                @unless ($isManagerAuth)
                    <div class="form-row">
                        <div class="field">
                            <label>Gestor responsável</label>
                            <select name="manager_id" class="select">
                                <option value="">—</option>
                                @foreach ($managerOptions as $m)
                                    <option value="{{ $m->id }}" {{ (string) old('manager_id', $user->manager_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>E-mail verificado em</label>
                            <input type="datetime-local" name="email_verified_at" class="input"
                                   value="{{ old('email_verified_at', $user->email_verified_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                @endunless

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
