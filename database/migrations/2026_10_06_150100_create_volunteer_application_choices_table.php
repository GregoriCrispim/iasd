<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_application_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_application_id')
                ->constrained('volunteer_applications')
                ->cascadeOnDelete();
            $table->string('ministry_slug', 64);
            $table->string('modality', 20); // lideranca | equipe
            $table->timestamps();

            $table->unique(['volunteer_application_id', 'ministry_slug'], 'vac_application_ministry_unique');
            $table->index('ministry_slug');
            $table->index('modality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_application_choices');
    }
};
