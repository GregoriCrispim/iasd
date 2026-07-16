<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $authUser = Auth::user();

        if ($authUser instanceof User && $authUser->isManager()) {
            // Gestor só pode editar colaboradores sob sua gestão.
            $data['manager_id'] = $authUser->id;
        }

        unset($data['role']);

        return $data;
    }

    protected function afterSave(): void
    {
        $authUser = Auth::user();

        $role = $this->form->getState()['role'] ?? null;

        if ($authUser instanceof User && $authUser->isManager()) {
            $role = 'collaborator';
        }

        if (is_string($role) && $role !== '') {
            $this->record->syncRoles([$role]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(function (): bool {
                    $user = Auth::user();
                    return $user instanceof User && $user->isSuperAdmin();
                }),
        ];
    }
}
