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
        Schema::create('cms_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_revision_id')->constrained('cms_revisions')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();
            $table->string('stage'); // manager | super_admin
            $table->string('decision'); // approved | rejected
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['cms_revision_id', 'stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_approvals');
    }
};
