<?php

namespace App\Http\Controllers\Doutores;

use App\Http\Controllers\Controller;
use App\Models\Doutores\Profile;
use App\Models\User;
use App\Support\DoutoresAuth;
use App\Support\DoutoresLogger;
use App\Support\DoutoresPoloScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Base dos controllers do módulo Doutores da Esperança (guard `doutores`).
 */
abstract class DoutoresController extends Controller
{
    protected function user(): User
    {
        $user = auth('doutores')->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $user->loadMissing('roles', 'doutoresProfile');

        return $user;
    }

    protected function perfilAtivo(): ?string
    {
        return DoutoresAuth::getActivePerfil(request()) ?? DoutoresAuth::defaultPerfil($this->user());
    }

    protected function isAdminPerfil(): bool
    {
        return $this->perfilAtivo() === DoutoresAuth::PERFIL_ADMIN
            && DoutoresAuth::canActAsAdmin($this->user());
    }

    protected function isManagerPerfil(): bool
    {
        return in_array($this->perfilAtivo(), [DoutoresAuth::PERFIL_ADMIN, DoutoresAuth::PERFIL_COORD], true)
            && DoutoresAuth::canActAsManager($this->user());
    }

    protected function abortUnlessAdmin(): void
    {
        if (! $this->isAdminPerfil()) {
            abort(403, 'Apenas administradores do módulo podem executar esta ação.');
        }
    }

    protected function abortUnlessManager(): void
    {
        if (! $this->isManagerPerfil()) {
            abort(403, 'Apenas coordenadores e administradores podem executar esta ação.');
        }
    }

    protected function unrestricted(): bool
    {
        return DoutoresPoloScope::isUnrestricted($this->user(), request());
    }

    /**
     * @return list<string>
     */
    protected function hospitalNames(bool $onlyActive = false): array
    {
        return DoutoresPoloScope::hospitalNamesInScope($this->user(), request(), $onlyActive);
    }

    protected function assertHospital(?string $hospital): void
    {
        if ($hospital === null || trim($hospital) === '') {
            if (! $this->unrestricted()) {
                abort(403, 'Informe um hospital do seu polo.');
            }

            return;
        }

        DoutoresPoloScope::assertHospitalInScope($this->user(), $hospital, request());
    }

    /**
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function scopeByHospital(Builder $query, string $column = 'hospital', bool $includeNull = false): Builder
    {
        return DoutoresPoloScope::filterPlantaoQuery($query, $this->user(), request(), $column, $includeNull);
    }

    protected function assertProfile(Profile $profile): void
    {
        DoutoresPoloScope::assertProfileInScope($this->user(), $profile, request());
    }

    /**
     * Perfil Doutores do usuário logado (criado sob demanda; ativo só para doutores_admin).
     */
    protected function profileFor(User $user): Profile
    {
        $profile = $user->doutoresProfile;

        if ($profile) {
            return $profile;
        }

        $profile = Profile::query()->create([
            'user_id' => $user->id,
            'status' => $user->hasAnyRoleName([DoutoresAuth::ROLE_ADMIN])
                ? Profile::STATUS_ATIVO
                : Profile::STATUS_PENDENTE,
        ]);

        $user->setRelation('doutoresProfile', $profile);

        return $profile;
    }

    protected function log(string $acao, string $categoria = 'sistema', ?string $descricao = null, ?string $alvo = null, ?string $documento = null, string $severidade = 'info'): void
    {
        DoutoresLogger::record($acao, $categoria, $descricao, $this->user(), $alvo, $documento, $severidade);
    }
}
