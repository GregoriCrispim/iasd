<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testemunho extends Model
{
    protected $table = 'doutores_testemunhos';

    protected $fillable = [
        'plantao_id',
        'user_id',
        'texto',
    ];

    public function plantao(): BelongsTo
    {
        return $this->belongsTo(Plantao::class, 'plantao_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}