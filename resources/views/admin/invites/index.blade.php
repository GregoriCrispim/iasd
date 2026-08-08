@extends('admin.layout')

@php $activeNav = 'invites'; @endphp
@section('title', 'Convites de membros')
@section('heading', 'Convites de membros')

@push('styles')
<style>
    .invite-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }
    .invite-page .card { margin-bottom: 0; }
    .invite-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }
    .invite-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        padding: 18px;
        background: #fff;
        border: 1px solid var(--adm-border);
        border-radius: var(--adm-radius);
        box-shadow: var(--adm-shadow);
    }
    .invite-stat__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eff6ff;
        color: var(--adm-primary);
        font-size: 21px;
    }
    .invite-stat__content { min-width: 0; }
    .invite-stat__value {
        display: block;
        color: var(--adm-text);
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
    }
    .invite-stat__label {
        display: block;
        margin-top: 4px;
        color: var(--adm-muted);
        font-size: 12.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .invite-code-box {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 18px 20px;
        overflow: hidden;
        border: 1px solid #86efac;
        border-radius: 14px;
        background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        box-shadow: var(--adm-shadow);
    }
    .invite-code-box__content { min-width: 0; }
    .invite-code-box__title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 6px;
        color: #166534;
        font-size: 14px;
        font-weight: 700;
    }
    .invite-code-box__code {
        display: block;
        color: #14532d;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: clamp(18px, 3vw, 25px);
        font-weight: 800;
        letter-spacing: .08em;
        overflow-wrap: anywhere;
    }
    .invite-code-box__warning {
        margin: 5px 0 0;
        color: #3f6212;
        font-size: 12.5px;
    }
    .invite-create-intro {
        margin: 0 0 18px;
        color: var(--adm-muted);
        font-size: 13.5px;
    }
    .invite-form {
        display: grid;
        grid-template-columns: minmax(220px, 2fr) minmax(130px, .7fr) minmax(210px, 1fr) auto;
        gap: 16px;
        align-items: end;
    }
    .invite-form .btn {
        min-height: 41px;
        justify-content: center;
        white-space: nowrap;
    }
    .invite-section-meta {
        margin-left: auto;
        color: var(--adm-muted);
        font-size: 12.5px;
        font-weight: 500;
    }
    .invite-prefix {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border: 1px solid var(--adm-border);
        border-radius: 7px;
        background: #f8fafc;
        color: #334155;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    .invite-code-cell {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 100%;
    }
    .invite-code-copy {
        width: 30px;
        height: 30px;
        padding: 0;
        justify-content: center;
    }
    .invite-description {
        display: block;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .invite-usage {
        min-width: 108px;
    }
    .invite-usage__label {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 5px;
        color: #334155;
        font-size: 12px;
        font-variant-numeric: tabular-nums;
    }
    .invite-usage__track {
        display: block;
        width: 100%;
        height: 6px;
        overflow: hidden;
        border: 0;
        border-radius: 999px;
        background: #e2e8f0;
        appearance: none;
        -webkit-appearance: none;
    }
    .invite-usage__track::-webkit-progress-bar {
        border-radius: inherit;
        background: #e2e8f0;
    }
    .invite-usage__track::-webkit-progress-value {
        border-radius: inherit;
        background: var(--adm-primary);
    }
    .invite-usage__track::-moz-progress-bar {
        border-radius: inherit;
        background: var(--adm-primary);
    }
    .invite-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        white-space: nowrap;
    }
    .invite-actions form { display: inline-flex; }
    .invite-actions .btn {
        width: 34px;
        height: 34px;
        padding: 0;
        justify-content: center;
    }
    .invite-member {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 180px;
    }
    .invite-member__avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .invite-member__name {
        display: block;
        font-weight: 600;
    }
    .invite-member__email {
        display: block;
        color: var(--adm-muted);
        font-size: 12px;
    }
    .invite-pagination { margin-top: 14px; }
    .invite-security-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 16px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #f8fafc;
        color: var(--adm-muted);
        font-size: 12.5px;
    }
    .invite-security-note i {
        color: var(--adm-primary);
        font-size: 16px;
    }
    @media (max-width: 1100px) {
        .invite-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .invite-form { grid-template-columns: 1fr 1fr; }
        .invite-form .field:first-child { grid-column: 1 / -1; }
        .invite-form .btn { width: 100%; }
    }
    @media (max-width: 700px) {
        .invite-stats { grid-template-columns: 1fr; }
        .invite-form { grid-template-columns: 1fr; }
        .invite-form .field:first-child { grid-column: auto; }
        .invite-code-box { align-items: stretch; flex-direction: column; }
        .invite-code-box .btn { width: 100%; justify-content: center; }
        .invite-section-meta { display: none; }
        .invite-table thead { display: none; }
        .invite-table,
        .invite-table tbody,
        .invite-table tr,
        .invite-table td { display: block; width: 100%; }
        .invite-table tbody tr { padding: 10px 16px; }
        .invite-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 8px 0;
            border: 0;
            text-align: right;
        }
        .invite-table tbody td::before {
            content: attr(data-label);
            color: var(--adm-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .04em;
            text-align: left;
            text-transform: uppercase;
        }
        .invite-table .invite-description { max-width: 210px; }
        .invite-table .invite-actions { justify-content: flex-end; }
        .invite-table .invite-member { min-width: 0; text-align: left; }
    }
</style>
@endpush

@section('content')
<div class="invite-page">
    <div class="invite-stats" aria-label="Resumo de convites e membros">
        <div class="invite-stat">
            <span class="invite-stat__icon"><i class="bi bi-ticket-perforated"></i></span>
            <span class="invite-stat__content">
                <strong class="invite-stat__value">{{ $stats['active_invites'] }}</strong>
                <span class="invite-stat__label">Convites disponíveis</span>
            </span>
        </div>
        <div class="invite-stat">
            <span class="invite-stat__icon"><i class="bi bi-person-plus"></i></span>
            <span class="invite-stat__content">
                <strong class="invite-stat__value">{{ $stats['available_uses'] }}</strong>
                <span class="invite-stat__label">Cadastros disponíveis</span>
            </span>
        </div>
        <div class="invite-stat">
            <span class="invite-stat__icon"><i class="bi bi-people"></i></span>
            <span class="invite-stat__content">
                <strong class="invite-stat__value">{{ $stats['members'] }}</strong>
                <span class="invite-stat__label">Membros cadastrados</span>
            </span>
        </div>
        <div class="invite-stat">
            <span class="invite-stat__icon"><i class="bi bi-person-check"></i></span>
            <span class="invite-stat__content">
                <strong class="invite-stat__value">{{ $stats['active_members'] }}</strong>
                <span class="invite-stat__label">Membros ativos</span>
            </span>
        </div>
    </div>

    @if (session('created_invite_code'))
        <div class="invite-code-box" role="status">
            <div class="invite-code-box__content">
                <p class="invite-code-box__title"><i class="bi bi-check-circle-fill"></i> Convite criado com sucesso</p>
                <code class="invite-code-box__code" id="createdInviteCode">{{ session('created_invite_code') }}</code>
                <p class="invite-code-box__warning">O código também fica disponível na listagem abaixo para consulta e cópia.</p>
            </div>
            <button type="button" class="btn btn-success" id="copyInviteCode">
                <i class="bi bi-copy"></i><span>Copiar código</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-plus-circle"></i> Criar convite</h2>
        </div>
        <div class="card-body">
            <p class="invite-create-intro">Crie um código individual ou com limite de usos. O convite só permite o cadastro; nenhum acesso é liberado até que uma conta seja criada.</p>
            <form method="POST" action="{{ route('admin.invites.store') }}" class="invite-form">
                @csrf
                <div class="field">
                    <label for="description">Identificação <span class="text-muted">(opcional)</span></label>
                    <input
                        type="text"
                        id="description"
                        name="description"
                        class="input {{ $errors->has('description') ? 'has-error' : '' }}"
                        value="{{ old('description') }}"
                        maxlength="160"
                        placeholder="Ex.: Coral jovem — Maria Silva"
                    >
                    @error('description')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="max_uses">Limite de usos</label>
                    <input
                        type="number"
                        id="max_uses"
                        name="max_uses"
                        class="input {{ $errors->has('max_uses') ? 'has-error' : '' }}"
                        min="1"
                        max="1000"
                        value="{{ old('max_uses', 1) }}"
                        required
                    >
                    @error('max_uses')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="expires_at">Validade <span class="text-muted">(opcional)</span></label>
                    <input
                        type="datetime-local"
                        id="expires_at"
                        name="expires_at"
                        class="input {{ $errors->has('expires_at') ? 'has-error' : '' }}"
                        value="{{ old('expires_at') }}"
                        min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}"
                    >
                    @error('expires_at')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn"><i class="bi bi-stars"></i> Gerar convite</button>
            </form>
            <div class="invite-security-note">
                <i class="bi bi-shield-lock"></i>
                <span>O código fica visível neste painel para facilitar o compartilhamento. Criar, desativar ou excluir um convite não autentica usuários nem libera a busca facial.</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-ticket-perforated"></i> Convites</h2>
            <span class="invite-section-meta">{{ $invites->total() }} registro(s)</span>
        </div>
        <div class="card-body flush">
            @if ($invites->isEmpty())
                <div class="empty-state"><i class="bi bi-ticket-perforated"></i>Nenhum convite criado ainda.</div>
            @else
                <div class="table-wrap">
                    <table class="adm-table invite-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Identificação</th>
                                <th>Usos</th>
                                <th>Validade</th>
                                <th>Status</th>
                                <th>Criado por</th>
                                <th class="text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invites as $invite)
                                @php
                                    $usagePercent = $invite->max_uses > 0
                                        ? min(100, (int) round(($invite->uses_count / $invite->max_uses) * 100))
                                        : 0;
                                    $status = $invite->statusLabel();
                                    $statusClass = match ($status) {
                                        'Ativo' => 'badge-green',
                                        'Expirado', 'Esgotado' => 'badge-amber',
                                        default => 'badge-gray',
                                    };
                                @endphp
                                <tr>
                                    <td data-label="Código">
                                        @if ($invite->code)
                                            <div class="invite-code-cell">
                                                <code class="invite-prefix">{{ $invite->code }}</code>
                                                <button
                                                    type="button"
                                                    class="btn btn-secondary btn-sm invite-code-copy"
                                                    data-copy-code="{{ $invite->code }}"
                                                    title="Copiar código"
                                                    aria-label="Copiar código {{ $invite->code }}"
                                                >
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Código indisponível (convite antigo)</span>
                                        @endif
                                    </td>
                                    <td data-label="Identificação"><span class="invite-description" title="{{ $invite->description }}">{{ $invite->description ?: 'Sem identificação' }}</span></td>
                                    <td data-label="Usos">
                                        <div class="invite-usage">
                                            <div class="invite-usage__label">
                                                <span>{{ $invite->uses_count }} de {{ $invite->max_uses }}</span>
                                                <span>{{ $usagePercent }}%</span>
                                            </div>
                                            <progress class="invite-usage__track" value="{{ $invite->uses_count }}" max="{{ $invite->max_uses }}" aria-label="{{ $usagePercent }}% utilizado"></progress>
                                        </div>
                                    </td>
                                    <td data-label="Validade">
                                        @if ($invite->expires_at)
                                            <span class="nowrap">{{ $invite->expires_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $invite->expires_at->format('H:i') }}</small>
                                        @else
                                            <span class="text-muted">Sem validade</span>
                                        @endif
                                    </td>
                                    <td data-label="Status"><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                                    <td data-label="Criado por">{{ $invite->creator?->name ?? 'Usuário removido' }}</td>
                                    <td data-label="Ações">
                                        <div class="invite-actions">
                                            @if (! $invite->isExhausted() && ! $invite->isExpired())
                                                <form method="POST" action="{{ route('admin.invites.toggle', $invite) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-sm" aria-label="{{ $invite->is_active ? 'Desativar convite' : 'Reativar convite' }}" title="{{ $invite->is_active ? 'Desativar' : 'Reativar' }}">
                                                        <i class="bi bi-{{ $invite->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($invite->uses_count === 0)
                                                <form method="POST" action="{{ route('admin.invites.destroy', $invite) }}" onsubmit="return admConfirm('O código deixará de funcionar imediatamente. Deseja excluir este convite?', this, { title: 'Excluir convite', confirmLabel: 'Excluir' });">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" aria-label="Excluir convite" title="Excluir">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="invite-pagination adm-pagination">{{ $invites->links() }}</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-people"></i> Membros cadastrados</h2>
            <span class="invite-section-meta">{{ $members->total() }} membro(s)</span>
        </div>
        <div class="card-body flush">
            @if ($members->isEmpty())
                <div class="empty-state"><i class="bi bi-people"></i>Nenhum membro cadastrado ainda.</div>
            @else
                <div class="table-wrap">
                    <table class="adm-table invite-table">
                        <thead>
                            <tr>
                                <th>Membro</th>
                                <th>Telefone</th>
                                <th>Nascimento</th>
                                <th>Congregação / vínculo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                @php
                                    $initials = collect(preg_split('/\s+/', trim($member->name)) ?: [])
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($part) => mb_substr($part, 0, 1))
                                        ->implode('');
                                @endphp
                                <tr>
                                    <td data-label="Membro">
                                        <div class="invite-member">
                                            <span class="invite-member__avatar">{{ $initials ?: '?' }}</span>
                                            <span>
                                                <span class="invite-member__name">{{ $member->name }}</span>
                                                <span class="invite-member__email">{{ $member->email }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td data-label="Telefone">{{ $member->phone ?: '—' }}</td>
                                    <td data-label="Nascimento">{{ $member->birth_date?->format('d/m/Y') ?? '—' }}</td>
                                    <td data-label="Vínculo">{{ $member->congregation ?: '—' }}</td>
                                    <td data-label="Status">
                                        <span class="badge {{ $member->is_active ? 'badge-green' : 'badge-gray' }}">
                                            {{ $member->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="invite-pagination adm-pagination">{{ $members->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        function copyText(code, button) {
            var original = button.innerHTML;
            var done = function () {
                button.innerHTML = '<i class="bi bi-check-lg"></i>';
                window.setTimeout(function () { button.innerHTML = original; }, 1800);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(done).catch(function () {});
                return;
            }

            var input = document.createElement('textarea');
            input.value = code;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            input.remove();
            done();
        }

        document.querySelectorAll('[data-copy-code]').forEach(function (button) {
            button.addEventListener('click', function () {
                copyText(button.getAttribute('data-copy-code') || '', button);
            });
        });

        var bannerButton = document.getElementById('copyInviteCode');
        var codeElement = document.getElementById('createdInviteCode');
        if (bannerButton && codeElement) {
            bannerButton.addEventListener('click', function () {
                copyText(codeElement.textContent.trim(), bannerButton);
                window.setTimeout(function () {
                    bannerButton.innerHTML = '<i class="bi bi-copy"></i><span>Copiar código</span>';
                }, 1800);
            });
        }
    })();
</script>
@endpush
