<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anexo extends Model
{
    /** @var array<string, string> */
    public const TIPOS = [
        'documento_identidade' => 'Documento de identidade',
        'comprovante_residencia' => 'Comprovante de residência',
        'vacinacao' => 'Carteira de vacinação',
        'certidao' => 'Certidão / certificado',
        'termo_assinado' => 'Termo assinado',
        'outro' => 'Outro',
    ];

    /** @var array<string, string> */
    public const STATUSES = [
        'enviado' => 'Enviado',
        'aprovado' => 'Aprovado',
        'rejeitado' => 'Rejeitado',
    ];

    protected $table = 'doutores_anexos';

    protected $fillable = [
        'user_id',
        'tipo',
        'nome_arquivo',
        'path',
        'mime',
        'tamanho',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}