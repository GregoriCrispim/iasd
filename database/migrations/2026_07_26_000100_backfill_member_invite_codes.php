<?php

use App\Models\MemberInvite;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Convites criados antes da coluna `code` só tinham prefixo/hash.
     * Regenera um código legível para os que ainda não têm código em claro
     * e ainda não foram utilizados.
     */
    public function up(): void
    {
        MemberInvite::query()
            ->whereNull('code')
            ->where('uses_count', 0)
            ->orderBy('id')
            ->each(function (MemberInvite $invite): void {
                $generated = MemberInvite::generateCode();

                $invite->forceFill([
                    'code' => $generated['code'],
                    'code_hash' => $generated['hash'],
                    'code_prefix' => $generated['prefix'],
                ])->save();
            });
    }

    public function down(): void
    {
        // Não reverte códigos já regenerados.
    }
};
