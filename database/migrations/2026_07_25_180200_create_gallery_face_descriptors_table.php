<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Descritores faciais (embeddings) extraídos das fotos do álbum.
     * O vetor de 128 dimensões é sempre gravado criptografado; nunca em claro.
     */
    public function up(): void
    {
        Schema::create('gallery_face_descriptors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->foreignId('gallery_photo_id')->constrained('gallery_photos')->cascadeOnDelete();
            $table->unsignedSmallInteger('face_index')->default(0);
            $table->float('box_x')->nullable();
            $table->float('box_y')->nullable();
            $table->float('box_w')->nullable();
            $table->float('box_h')->nullable();
            $table->float('score')->nullable();
            $table->string('model_version', 40);
            $table->text('descriptor');
            $table->timestamps();

            $table->index(['gallery_album_id', 'model_version']);
            $table->index('gallery_photo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_face_descriptors');
    }
};
