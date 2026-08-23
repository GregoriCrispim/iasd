<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $liderId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia_lider')->value('id');
        if (! $liderId) {
            $liderId = DB::table('roles')->insertGetId([
                'name' => 'fotografia_lider',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $colaboradorId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia_colaborador')->value('id');
        if (! $colaboradorId) {
            DB::table('roles')->insert([
                'name' => 'fotografia_colaborador',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $legacyId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia')->value('id');
        if (! $legacyId) {
            return;
        }

        DB::table('model_has_roles')
            ->where('role_id', $legacyId)
            ->update(['role_id' => $liderId]);

        DB::table('roles')->where('id', $legacyId)->delete();
    }

    public function down(): void
    {
        $legacyId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia')->value('id');
        if (! $legacyId) {
            $legacyId = DB::table('roles')->insertGetId([
                'name' => 'fotografia',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $liderId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia_lider')->value('id');
        if ($liderId) {
            DB::table('model_has_roles')
                ->where('role_id', $liderId)
                ->update(['role_id' => $legacyId]);
            DB::table('roles')->where('id', $liderId)->delete();
        }

        $colaboradorId = DB::table('roles')->where('guard_name', 'web')->where('name', 'fotografia_colaborador')->value('id');
        if ($colaboradorId) {
            DB::table('model_has_roles')
                ->where('role_id', $colaboradorId)
                ->update(['role_id' => $legacyId]);
            DB::table('roles')->where('id', $colaboradorId)->delete();
        }
    }
};
