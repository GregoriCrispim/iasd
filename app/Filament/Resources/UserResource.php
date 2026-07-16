<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\PagesRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $navigationGroup = 'Gestão';

    public static function form(Form $form): Form
    {
        $authUser = Auth::user();
        $isManager = $authUser instanceof User && $authUser->isManager();

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->visible(fn () => !$isManager),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state)),
                Forms\Components\Select::make('role')
                    ->label('Perfil')
                    ->options(function () use ($isManager) {
                        if ($isManager) {
                            return ['collaborator' => 'Colaborador'];
                        }

                        return [
                            'manager' => 'Gestor',
                            'collaborator' => 'Colaborador',
                        ];
                    })
                    ->default(fn () => $isManager ? 'collaborator' : null)
                    ->required()
                    ->afterStateHydrated(function (Forms\Components\Select $component, ?User $record) use ($isManager) {
                        if ($record) {
                            $component->state($record->roles->pluck('name')->first());
                            return;
                        }

                        if ($isManager) {
                            $component->state('collaborator');
                        }
                    }),
                Forms\Components\Select::make('manager_id')
                    ->label('Gestor responsável')
                    ->relationship('manager', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Perfil')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Gestor')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function (): bool {
                            $user = Auth::user();
                            return $user instanceof User && $user->isSuperAdmin();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PagesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $authUser = Auth::user();

        if (!$authUser instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($authUser->isSuperAdmin()) {
            return $query;
        }

        if ($authUser->isManager()) {
            return $query->where(function (Builder $builder) use ($authUser) {
                $builder
                    ->where('manager_id', $authUser->id)
                    ->orWhere('id', $authUser->id);
            });
        }

        return $query->where('id', $authUser->id);
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->hasAnyRoleName(['super_admin', 'manager']);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->hasAnyRoleName(['super_admin', 'manager']);
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->isSuperAdmin();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
