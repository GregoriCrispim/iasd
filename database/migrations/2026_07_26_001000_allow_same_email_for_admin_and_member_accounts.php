<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Contas de painel e de membro são cadastros distintos e podem
     * compartilhar o mesmo e-mail. A unicidade passa a ser por papel.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->index('email');
        });

        // Remove o papel "member" de quem também é admin — o cadastro de membro
        // deve ser uma conta separada, sem acesso ao painel.
        $adminRoleIds = DB::table('roles')
            ->where('guard_name', 'web')
            ->whereIn('name', ['super_admin', 'manager', 'collaborator', 'fotografia'])
            ->pluck('id');

        $memberRoleId = DB::table('roles')
            ->where('guard_name', 'web')
            ->where('name', 'member')
            ->value('id');

        if ($adminRoleIds->isEmpty() || ! $memberRoleId) {
            return;
        }

        $adminUserIds = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->whereIn('role_id', $adminRoleIds)
            ->pluck('model_id')
            ->unique();

        if ($adminUserIds->isEmpty()) {
            return;
        }

        DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->where('role_id', $memberRoleId)
            ->whereIn('model_id', $adminUserIds)
            ->delete();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->unique('email');
        });
    }
};
