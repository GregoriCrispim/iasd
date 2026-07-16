<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\CmsPage;
use App\Models\User;

class PagesRelationManager extends RelationManager
{
    protected static string $relationship = 'pages';

    protected static ?string $title = 'Páginas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('info')
                    ->content('Use as ações da tabela para vincular/remover páginas e ajustar permissões.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('label'))
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Página')->searchable(),
                Tables\Columns\TextColumn::make('route_name')->label('Rota')->searchable(),
                Tables\Columns\IconColumn::make('cms_enabled')->label('CMS')->boolean(),
                Tables\Columns\IconColumn::make('pivot.can_access')->label('Acessar')->boolean(),
                Tables\Columns\IconColumn::make('pivot.can_edit')->label('Editar')->boolean(),
                Tables\Columns\IconColumn::make('pivot.can_approve')->label('Aprovar')->boolean(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('attachPage')
                    ->label('Vincular página')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\Select::make('cms_page_id')
                            ->label('Página')
                            ->options(function (): array {
                                $authUser = Auth::user();
                                $query = CmsPage::query()->orderBy('label');

                                if ($authUser instanceof User && $authUser->isManager()) {
                                    $allowedPageIds = $authUser->pages()
                                        ->wherePivot('can_access', true)
                                        ->pluck('cms_pages.id');
                                    $query->whereIn('id', $allowedPageIds);
                                }

                                return $query->pluck('label', 'id')->all();
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\Toggle::make('can_access')->label('Acessar')->default(true),
                        Forms\Components\Toggle::make('can_edit')->label('Editar')->default(false),
                        Forms\Components\Toggle::make('can_approve')->label('Aprovar')->default(false),
                    ])
                    ->action(function (array $data): void {
                        $this->getOwnerRecord()
                            ->pages()
                            ->syncWithoutDetaching([
                                $data['cms_page_id'] => [
                                    'can_access' => (bool) ($data['can_access'] ?? true),
                                    'can_edit' => (bool) ($data['can_edit'] ?? false),
                                    'can_approve' => (bool) ($data['can_approve'] ?? false),
                                ],
                            ]);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('editPermissions')
                    ->label('Permissões')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->fillForm(fn (CmsPage $record): array => [
                        'can_access' => (bool) ($record->pivot?->can_access ?? true),
                        'can_edit' => (bool) ($record->pivot?->can_edit ?? false),
                        'can_approve' => (bool) ($record->pivot?->can_approve ?? false),
                    ])
                    ->form([
                        Forms\Components\Toggle::make('can_access')->label('Acessar'),
                        Forms\Components\Toggle::make('can_edit')->label('Editar'),
                        Forms\Components\Toggle::make('can_approve')->label('Aprovar'),
                    ])
                    ->action(function (CmsPage $record, array $data): void {
                        $this->getOwnerRecord()
                            ->pages()
                            ->updateExistingPivot($record->id, [
                                'can_access' => (bool) ($data['can_access'] ?? true),
                                'can_edit' => (bool) ($data['can_edit'] ?? false),
                                'can_approve' => (bool) ($data['can_approve'] ?? false),
                            ]);
                    }),
                Tables\Actions\DetachAction::make()->label('Remover'),
            ]);
    }
}

