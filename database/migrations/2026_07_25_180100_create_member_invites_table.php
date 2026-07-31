<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convites de cadastro de membros, gerados apenas pelo super-admin.
     * O código nunca é salvo em claro: guardamos apenas o hash e um prefixo
     * curto para identificação visual na listagem.
     */
    public function up(): void
    {
        Schema::create('member_invites', function (Blueprint $table) {
            $table->id();
            $table->string('code_hash', 64)->unique();
            $table->string('code_prefix', 12)->nullable();
            $table->string('description', 160)->nullable();
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('uses_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'expires_at']);
        });

        Schema::create('member_invite_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_invite_id')->constrained('member_invites')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_invite_uses');
        Schema::dropIfExists('member_invites');
    }
};
