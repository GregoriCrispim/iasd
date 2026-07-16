<?php

namespace App\Filament\Resources\CmsPageResource\Pages;

use App\Filament\Resources\CmsPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListCmsPages extends ListRecords
{
    protected static string $resource = CmsPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sincronizar rotas')
                ->icon('heroicon-o-arrow-path')
                ->action(function (): void {
                    Artisan::call('cms:sync-pages');
                })
                ->successNotificationTitle('Páginas sincronizadas'),
        ];
    }
}
