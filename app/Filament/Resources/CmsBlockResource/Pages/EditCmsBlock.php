<?php

namespace App\Filament\Resources\CmsBlockResource\Pages;

use App\Filament\Resources\CmsBlockResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCmsBlock extends EditRecord
{
    protected static string $resource = CmsBlockResource::class;

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
