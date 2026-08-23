<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'collaborator', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'fotografia_lider', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'fotografia_colaborador', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'member', 'guard_name' => 'web']);

        $user = User::query()->updateOrCreate(
            ['email' => 'gregoridesbravador@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => 'Admin123',
                'email_verified_at' => Carbon::now(),
            ],
        );

        $user->syncRoles(['super_admin']);

        $fotoUser = User::query()->updateOrCreate(
            ['email' => 'fotografia@adventistascentralbrasilia.org'],
            [
                'name' => 'Ministério de Fotografia',
                'password' => 'Foto@2026',
                'email_verified_at' => Carbon::now(),
            ],
        );

        $fotoUser->syncRoles(['fotografia_lider']);
    }
}
