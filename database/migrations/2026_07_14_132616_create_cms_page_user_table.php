<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration can be attempted before cms_pages due to filename ordering.
        // Also, a previous failed attempt may have created cms_page_user partially.
        if (Schema::hasTable('cms_page_user')) {
            Schema::drop('cms_page_user');
        }

        if (!Schema::hasTable('cms_pages')) {
            Schema::create('cms_pages', function (Blueprint $table) {
                $table->id();
                $table->string('route_name')->unique();
                $table->string('view_path')->nullable();
                $table->string('label');
                $table->string('section_slug')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('cms_enabled')->default(true);
                $table->timestamps();
            });
        }

        Schema::create('cms_page_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->boolean('can_access')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_approve')->default(false);

            $table->timestamps();

            $table->unique(['cms_page_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_page_user');
    }
};
