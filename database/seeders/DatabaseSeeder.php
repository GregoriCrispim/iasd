<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdminRole = Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'collaborator', 'guard_name' => 'web']);

        User::query()->updateOrCreate(
            ['email' => 'gregoridesbravador@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => 'Admin123',
                'email_verified_at' => Carbon::now(),
            ],
        );

        User::query()
            ->where('email', 'gregoridesbravador@gmail.com')
            ->firstOrFail()
            ->syncRoles([$superAdminRole]);
    }
}
