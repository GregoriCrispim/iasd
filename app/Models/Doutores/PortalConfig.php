<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;

class PortalConfig extends Model
{
    protected $table = 'doutores_portal_config';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'titulo_hero',
        'subtitulo_hero',
        'texto_sobre',
        'contato_email',
        'contato_telefone',
        'contato_endereco',
    ];
}