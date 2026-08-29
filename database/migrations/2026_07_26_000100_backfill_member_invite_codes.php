<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Histórico: backfill de códigos de convite.
     * A funcionalidade de convites foi removida; este passo não faz mais nada.
     */
    public function up(): void
    {
        // no-op
    }

    public function down(): void
    {
        // no-op
    }
};
