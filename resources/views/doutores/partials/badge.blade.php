@php
    $badgeMap = [
        'ativo' => 'badge-green', 'aprovado' => 'badge-green', 'confirmada' => 'badge-green', 'concluido' => 'badge-green',
        'realizado' => 'badge-green', 'publicado' => 'badge-green',
        'aberto' => 'badge-blue', 'encaminhado' => 'badge-blue', 'em_andamento' => 'badge-blue', 'enviado' => 'badge-blue',
        'pendente' => 'badge-amber', 'fechado' => 'badge-amber', 'warning' => 'badge-amber',
        'rascunho' => 'badge-gray', 'inativo' => 'badge-gray', 'info' => 'badge-blue',
        'cancelada' => 'badge-red', 'cancelado' => 'badge-red', 'rejeitado' => 'badge-red', 'recusada' => 'badge-red', 'error' => 'badge-red',
    ];
    $badgeLabels = array_merge(
        \App\Models\Doutores\Plantao::STATUSES,
        \App\Models\Doutores\Inscricao::STATUSES,
        \App\Models\Doutores\Anexo::STATUSES,
        \App\Models\Doutores\SolicitacaoEstudo::STATUSES,
        ['ativo' => 'Ativo', 'inativo' => 'Inativo', 'pendente' => 'Pendente', 'publicado' => 'Publicado', 'rascunho' => 'Rascunho', 'info' => 'Info', 'warning' => 'Atenção', 'error' => 'Erro']
    );
    $badgeStatus = (string) ($status ?? '');
@endphp
<span class="badge {{ $badgeMap[$badgeStatus] ?? 'badge-gray' }}">{{ $badgeLabels[$badgeStatus] ?? ($badgeStatus !== '' ? $badgeStatus : '—') }}</span>
