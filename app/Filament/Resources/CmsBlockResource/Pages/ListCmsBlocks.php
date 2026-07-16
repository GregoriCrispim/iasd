<?php

namespace App\Filament\Resources\CmsBlockResource\Pages;

use App\Filament\Resources\CmsBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCmsBlocks extends ListRecords
{
    protected static string $resource = CmsBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
