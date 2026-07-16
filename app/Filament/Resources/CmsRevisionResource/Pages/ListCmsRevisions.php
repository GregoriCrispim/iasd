<?php

namespace App\Filament\Resources\CmsRevisionResource\Pages;

use App\Filament\Resources\CmsRevisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCmsRevisions extends ListRecords
{
    protected static string $resource = CmsRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
