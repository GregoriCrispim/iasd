<?php

namespace App\Console\Commands;

use App\Models\Doutores\Profile;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DoutoresCriarAdmin extends Command
{
    protected $signature = 'doutores:admin {email} {--name=Administrador Doutores} {--password=}';

    protected $description = 'Cria (ou reativa) um administrador do portal Doutores da Esperança. Pode reutilizar o mesmo e-mail de uma conta do CMS/site.';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $user = User::query()->where('email', $email)->first();

        $senha = $this->option('password') ?: null;
        $gerada = false;
        if (! $user && ! $senha) {
            $senha = Str::password(12, symbols: false);
            $gerada = true;
        }

        Role::query()->firstOrCreate(['name' => DoutoresAuth::ROLE_ADMIN, 'guard_name' => 'web']);

        $user ??= User::create([
            'name' => $this->option('name'),
            'email' => $email,
            'password' => $senha,
            'is_active' => true,
        ]);

        if ($user->wasRecentlyCreated === false) {
            $payload = [
                'name' => $this->option('name') ?: $user->name,
                'is_active' => true,
            ];
            if ($senha) {
                $payload['password'] = $senha;
            }
            $user->update($payload);
        }

        $user->syncDoutoresRoles(array_values(array_unique(array_merge($user->doutoresRoleNames(), [DoutoresAuth::ROLE_ADMIN]))));
        Profile::query()->updateOrCreate(
            ['user_id' => $user->id],
            ['status' => Profile::STATUS_ATIVO, 'termos_aceitos_em' => now()]
        );

        $this->info("Administrador Doutores pronto: {$email}");
        if ($gerada) {
            $this->line("Senha temporária: {$senha}");
        }

        return self::SUCCESS;
    }
}
