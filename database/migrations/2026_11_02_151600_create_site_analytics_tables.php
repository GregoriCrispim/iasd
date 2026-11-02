<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->timestamp('viewed_at');
            $table->string('method', 10)->default('GET');
            $table->string('path', 500);
            $table->string('route_name', 120)->nullable();
            $table->unsignedSmallInteger('status')->nullable();
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

            $table->index(['viewed_at']);
            $table->index(['route_name', 'viewed_at']);
            $table->index(['path', 'viewed_at']);
            $table->index(['visitor_key']);
            $table->index(['is_bot', 'viewed_at']);
            $table->index(['device_type']);
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('submitted_at');
            $table->string('form_key', 80);
            $table->string('route_name', 120)->nullable();
            $table->string('path', 500)->nullable();
            $table->boolean('success')->default(true);
            $table->string('visitor_key', 64);
            $table->string('ip_hash', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('platform', 40)->nullable();
            $table->string('accept_language', 120)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('country_code', 8)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['submitted_at']);
            $table->index(['form_key', 'submitted_at']);
            $table->index(['is_bot', 'submitted_at']);
            $table->index(['visitor_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('page_views');
    }
};
