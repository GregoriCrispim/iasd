<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro mínimo de consentimento para a busca facial. Não guarda selfie
     * nem descriptor: apenas o fato de que o titular consentiu, a versão do
     * termo, a origem (câmera/upload) e a declaração de responsável (menor).
     */
    public function up(): void
    {
        Schema::create('face_search_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('gallery_album_id')->nullable()->constrained('gallery_albums')->nullOnDelete();
            $table->string('terms_version', 20);
            $table->string('source', 20)->default('upload');
            $table->boolean('is_guardian_declared')->default(false);
            $table->string('ip_hash', 64)->nullable();
            $table->timestamp('consented_at');
            $table->timestamps();

            $table->index(['user_id', 'gallery_album_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_search_consents');
    }
};
