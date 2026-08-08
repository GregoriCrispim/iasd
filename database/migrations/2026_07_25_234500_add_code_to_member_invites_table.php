<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * O código do convite fica visível no painel administrativo (somente super-admin).
     */
    public function up(): void
    {
        Schema::table('member_invites', function (Blueprint $table) {
            $table->string('code', 40)->nullable()->after('code_hash');
        });
    }

    public function down(): void
    {
        Schema::table('member_invites', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
