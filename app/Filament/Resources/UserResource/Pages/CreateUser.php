<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $authUser = Auth::user();

        $data['created_by'] = $authUser instanceof User ? $authUser->id : null;

        if ($authUser instanceof User && $authUser->isManager()) {
            $data['manager_id'] = $authUser->id;
        }

        // 'role' is a virtual field (Spatie role), not a DB column.
        unset($data['role']);

        return $data;
    }

    protected function afterCreate(): void
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
}
