<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $adminRoleId = DB::table('roles')
            ->where('guard_name', 'web')
            ->where('name', 'admin')
            ->value('id');

        if (! $adminRoleId) {
            DB::table('roles')->insert([
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $superAdminRoleId = DB::table('roles')
            ->where('guard_name', 'web')
            ->where('name', 'super_admin')
            ->value('id');

        if (! $superAdminRoleId) {
            $superAdminRoleId = DB::table('roles')->insertGetId([
                'name' => 'super_admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $user = User::query()
            ->where('email', 'gregoridesbravador@gmail.com')
            ->first();

        if (! $user) {
            return;
        }

        // Garante o papel Super Admin exclusivo para esta conta.
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->where('model_id', $user->id)
            ->delete();

        DB::table('model_has_roles')->insert([
            'role_id' => $superAdminRoleId,
            'model_type' => User::class,
            'model_id' => $user->id,
        ]);
    }

    public function down(): void
    {
        $adminRoleId = DB::table('roles')
            ->where('guard_name', 'web')
            ->where('name', 'admin')
            ->value('id');

        if (! $adminRoleId) {
            return;
        }

        DB::table('model_has_roles')->where('role_id', $adminRoleId)->delete();
        DB::table('roles')->where('id', $adminRoleId)->delete();
    }
};
