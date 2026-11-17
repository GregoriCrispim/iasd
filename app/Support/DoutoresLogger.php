<?php

namespace App\Support;

use App\Models\Doutores\LogEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

/**
 * Registro de auditoria do módulo Doutores da Esperança.
 */
class DoutoresLogger
{
    public static function record(
        string $acao,
        string $categoria = 'sistema',
        ?string $descricao = null,
        ?User $executor = null,
        ?string $usuarioAlvo = null,
        ?string $documentoAlvo = null,
        string $severidade = 'info',
    ): void {
        try {
            $request = request();
            $executor ??= auth('doutores')->user();
            $perfil = $executor && $request instanceof Request && $request->hasSession()
                ? DoutoresAuth::getActivePerfil($request)
                : null;

            LogEntry::create([
                'registrado_em' => now(),
                'acao' => $acao,
                'categoria' => $categoria,
                'descricao' => $descricao,
                'executado_por' => $executor?->name,
                'executado_por_perfil' => $perfil,
                'usuario_alvo' => $usuarioAlvo,
                'documento_alvo' => $documentoAlvo,
                'endereco_ip' => $request instanceof Request ? $request->ip() : null,
                'severidade' => $severidade,
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
