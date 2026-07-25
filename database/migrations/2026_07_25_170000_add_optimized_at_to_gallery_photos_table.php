<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            // Nulo = ainda não otimizada (envio antes do GD existir no servidor,
            // ou acervo importado da pasta antiga). Alimenta `galeria:otimizar`.
            $table->timestamp('optimized_at')->nullable()->after('faces_scanned_at');
            $table->index('optimized_at');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropIndex(['optimized_at']);
            $table->dropColumn('optimized_at');
        });
    }
};
