<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->timestamp('visited_at');
            $table->string('visitor_key', 64);
            $table->string('ip_hash', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('platform', 40)->nullable();
            $table->string('accept_language', 120)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('country_code', 8)->nullable();
            $table->json('query')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->timestamps();

            $table->index(['campaign_id', 'visited_at']);
            $table->index(['campaign_id', 'visitor_key']);
            $table->index(['campaign_id', 'device_type']);
            $table->index(['campaign_id', 'is_bot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_visits');
    }
};
