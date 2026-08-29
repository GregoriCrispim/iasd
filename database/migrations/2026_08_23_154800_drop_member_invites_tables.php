<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('member_invite_uses');
        Schema::dropIfExists('member_invites');
    }

    public function down(): void
    {
        Schema::create('member_invites', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('code_hash', 64)->unique();
            $table->string('code_prefix', 16)->index();
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('uses_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('member_invite_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_invite_id')->constrained('member_invites')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }
};
