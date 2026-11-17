<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (['doutores_admin', 'doutores_coordenador', 'doutores_voluntario'] as $name) {
            $exists = DB::table('roles')
                ->where('guard_name', 'web')
                ->where('name', $name)
                ->exists();

            if (! $exists) {
                DB::table('roles')->insert([
                    'name' => $name,
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('roles')
            ->where('guard_name', 'web')
            ->whereIn('name', ['doutores_admin', 'doutores_coordenador', 'doutores_voluntario'])
            ->delete();
    }
};
