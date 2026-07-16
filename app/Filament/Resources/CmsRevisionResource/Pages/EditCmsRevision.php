<?php

namespace App\Filament\Resources\CmsRevisionResource\Pages;

use App\Filament\Resources\CmsRevisionResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCmsRevision extends EditRecord
{
    protected static string $resource = CmsRevisionResource::class;

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
