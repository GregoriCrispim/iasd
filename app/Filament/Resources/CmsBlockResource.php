<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsBlockResource\Pages\CreateCmsBlock;
use App\Filament\Resources\CmsBlockResource\Pages\EditCmsBlock;
use App\Filament\Resources\CmsBlockResource\Pages\ListCmsBlocks;
use App\Models\CmsBlock;
use App\Models\CmsPage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CmsBlockResource extends Resource
{
    protected static ?string $model = CmsBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Blocos';
    protected static ?string $navigationGroup = 'CMS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cms_page_id')
                    ->label('Página')
                    ->relationship('page', 'label')
                    ->options(fn () => CmsPage::query()->orderBy('label')->pluck('label', 'id')->all())
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('block_key')
                    ->label('Chave do bloco')
                    ->helperText('Ex.: intro, conteudo, destaque')
                    ->required(),
                Forms\Components\TextInput::make('label')
                    ->label('Nome')
                    ->required(),
                Forms\Components\TextInput::make('published_revision_id')
                    ->label('Revisão publicada')
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page.label')->label('Página')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('block_key')->label('Chave')->searchable(),
                Tables\Columns\TextColumn::make('label')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('published_revision_id')->label('Publicada')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Atualizado')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsBlocks::route('/'),
            'create' => CreateCmsBlock::route('/create'),
            'edit' => EditCmsBlock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('page');
        $user = Auth::user();

        if (!$user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('page', function (Builder $pageQuery) use ($user) {
            $pageQuery
                ->where('cms_enabled', true)
                ->whereHas('users', function (Builder $userQuery) use ($user) {
                    $userQuery
                        ->where('users.id', $user->id)
                        ->where('cms_page_user.can_edit', true);
                });
        });
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return self::canViewAny();
    }

    public static function canEdit($record): bool
    {
        return self::canViewAny();
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
