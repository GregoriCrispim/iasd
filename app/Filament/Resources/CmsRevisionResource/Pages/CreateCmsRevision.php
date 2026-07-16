<?php

namespace App\Filament\Resources\CmsRevisionResource\Pages;

use App\Filament\Resources\CmsRevisionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateCmsRevision extends CreateRecord
{
    protected static string $resource = CmsRevisionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['created_by'] = $user instanceof User ? $user->id : null;

        return $data;
    }
}
