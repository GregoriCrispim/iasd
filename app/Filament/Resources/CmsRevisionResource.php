<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsRevisionResource\Pages\CreateCmsRevision;
use App\Filament\Resources\CmsRevisionResource\Pages\EditCmsRevision;
use App\Filament\Resources\CmsRevisionResource\Pages\ListCmsRevisions;
use App\Models\CmsRevision;
use App\Models\CmsBlock;
use App\Models\CmsApproval;
use App\Models\User;
use App\Filament\Forms\Components\TinyMceEditor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/** @phpstan-import-type */
class CmsRevisionResource extends Resource
{
    protected static ?string $model = CmsRevision::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Revisões';
    protected static ?string $navigationGroup = 'CMS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cms_block_id')
                    ->label('Bloco')
                    ->options(function (): array {
                        $user = Auth::user();

                        $query = CmsBlock::query()
                            ->with('page')
                            ->whereHas('page', fn (Builder $pageQuery) => $pageQuery->where('cms_enabled', true))
                            ->orderBy('id');

                        if ($user instanceof User && !$user->isSuperAdmin()) {
                            $query->whereHas('page.users', function (Builder $userQuery) use ($user) {
                                $userQuery
                                    ->where('users.id', $user->id)
                                    ->where('cms_page_user.can_edit', true);
                            });
                        }

                        return $query->get()
                            ->mapWithKeys(fn (CmsBlock $block) => [
                                $block->id => ($block->page?->label ? "{$block->page->label} — " : '') . $block->label . " ({$block->block_key})",
                            ])
                            ->all();
                    })
                    ->searchable()
                    ->required(),

                TinyMceEditor::make('html')
                    ->label('Conteúdo')
                    ->uploadImageUrl(fn () => route('admin.uploads.image'))
                    ->uploadFileUrl(fn () => route('admin.uploads.file'))
                    ->required(),

                Forms\Components\Hidden::make('status')->default('draft'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('block.page.label')->label('Página')->searchable(),
                Tables\Columns\TextColumn::make('block.label')->label('Bloco')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('author.name')->label('Autor')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Criado')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('approvals_count')->label('Aprovações')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->label('Publicado')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        CmsRevision::STATUS_DRAFT => 'Rascunho',
                        CmsRevision::STATUS_PENDING_MANAGER => 'Aguardando gestor',
                        CmsRevision::STATUS_PENDING_SUPER_ADMIN => 'Aguardando super-admin',
                        CmsRevision::STATUS_REJECTED => 'Rejeitado',
                        CmsRevision::STATUS_PUBLISHED => 'Publicado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return false;
                        }

                        if ($user->isSuperAdmin()) {
                            return true;
                        }

                        if ($record->created_by !== $user->id) {
                            return false;
                        }

                        return in_array($record->status, [CmsRevision::STATUS_DRAFT, CmsRevision::STATUS_REJECTED], true);
                    }),
                Tables\Actions\Action::make('submit')
                    ->label('Enviar')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return false;
                        }

                        if (!in_array($record->status, [CmsRevision::STATUS_DRAFT, CmsRevision::STATUS_REJECTED], true)) {
                            return false;
                        }

                        $routeName = $record->block?->page?->route_name;
                        if (!is_string($routeName) || $routeName === '') {
                            return false;
                        }

                        if ($user->isSuperAdmin()) {
                            return true;
                        }

                        return $record->created_by === $user->id && $user->canEditPage($routeName);
                    })
                    ->action(function (CmsRevision $record): void {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return;
                        }

                        if ($user->isSuperAdmin()) {
                            CmsApproval::create([
                                'cms_revision_id' => $record->id,
                                'approver_id' => $user->id,
                                'stage' => 'super_admin',
                                'decision' => 'approved',
                                'comment' => null,
                            ]);

                            $record->block()->update([
                                'published_revision_id' => $record->id,
                            ]);

                            $record->update([
                                'status' => CmsRevision::STATUS_PUBLISHED,
                                'submitted_at' => now(),
                                'published_at' => now(),
                            ]);

                            return;
                        }

                        $nextStatus = $user->isManager()
                            ? CmsRevision::STATUS_PENDING_SUPER_ADMIN
                            : CmsRevision::STATUS_PENDING_MANAGER;

                        $record->update([
                            'status' => $nextStatus,
                            'submitted_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('approveManager')
                    ->label('Aprovar (Gestor)')
                    ->icon('heroicon-o-check')
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User || !$user->isManager()) {
                            return false;
                        }

                        if ($record->status !== CmsRevision::STATUS_PENDING_MANAGER) {
                            return false;
                        }

                        $page = $record->block?->page;
                        if (!$page) {
                            return false;
                        }

                        if (!$user->canApprovePage($page->route_name)) {
                            return false;
                        }

                        return $record->author?->manager_id === $user->id;
                    })
                    ->action(function (CmsRevision $record): void {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return;
                        }

                        CmsApproval::create([
                            'cms_revision_id' => $record->id,
                            'approver_id' => $user->id,
                            'stage' => 'manager',
                            'decision' => 'approved',
                            'comment' => null,
                        ]);

                        $record->update([
                            'status' => CmsRevision::STATUS_PENDING_SUPER_ADMIN,
                        ]);
                    }),

                Tables\Actions\Action::make('rejectManager')
                    ->label('Rejeitar (Gestor)')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User || !$user->isManager()) {
                            return false;
                        }

                        if ($record->status !== CmsRevision::STATUS_PENDING_MANAGER) {
                            return false;
                        }

                        $page = $record->block?->page;
                        if (!$page) {
                            return false;
                        }

                        if (!$user->canApprovePage($page->route_name)) {
                            return false;
                        }

                        return $record->author?->manager_id === $user->id;
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Motivo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (CmsRevision $record, array $data): void {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return;
                        }

                        CmsApproval::create([
                            'cms_revision_id' => $record->id,
                            'approver_id' => $user->id,
                            'stage' => 'manager',
                            'decision' => 'rejected',
                            'comment' => $data['comment'] ?? null,
                        ]);

                        $record->update([
                            'status' => CmsRevision::STATUS_REJECTED,
                        ]);
                    }),

                Tables\Actions\Action::make('approveSuperAdmin')
                    ->label('Aprovar (Super)')
                    ->icon('heroicon-o-shield-check')
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User || !$user->isSuperAdmin()) {
                            return false;
                        }

                        return $record->status === CmsRevision::STATUS_PENDING_SUPER_ADMIN;
                    })
                    ->action(function (CmsRevision $record): void {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return;
                        }

                        CmsApproval::create([
                            'cms_revision_id' => $record->id,
                            'approver_id' => $user->id,
                            'stage' => 'super_admin',
                            'decision' => 'approved',
                            'comment' => null,
                        ]);

                        $record->block()->update([
                            'published_revision_id' => $record->id,
                        ]);

                        $record->update([
                            'status' => CmsRevision::STATUS_PUBLISHED,
                            'published_at' => now(),
                        ]);
                    }),

                Tables\Actions\Action::make('rejectSuperAdmin')
                    ->label('Rejeitar (Super)')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->visible(function (CmsRevision $record): bool {
                        $user = Auth::user();
                        if (!$user instanceof User || !$user->isSuperAdmin()) {
                            return false;
                        }

                        return $record->status === CmsRevision::STATUS_PENDING_SUPER_ADMIN;
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Motivo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (CmsRevision $record, array $data): void {
                        $user = Auth::user();
                        if (!$user instanceof User) {
                            return;
                        }

                        CmsApproval::create([
                            'cms_revision_id' => $record->id,
                            'approver_id' => $user->id,
                            'stage' => 'super_admin',
                            'decision' => 'rejected',
                            'comment' => $data['comment'] ?? null,
                        ]);

                        $record->update([
                            'status' => CmsRevision::STATUS_REJECTED,
                        ]);
                    }),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (CmsRevision $record) => route('admin.cms.preview', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('compare')
                    ->label('Comparar')
                    ->icon('heroicon-o-arrows-right-left')
                    ->url(fn (CmsRevision $record) => route('admin.cms.compare', $record))
                    ->openUrlInNewTab(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsRevisions::route('/'),
            'create' => CreateCmsRevision::route('/create'),
            'edit' => EditCmsRevision::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->hasAnyRoleName(['super_admin', 'manager', 'collaborator']);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        // Allow create if user has at least one editable page.
        return $user->pages()->wherePivot('can_edit', true)->exists();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['block.page', 'author'])->withCount('approvals');
        $user = Auth::user();

        if (!$user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query
                ->whereHas('block.page.users', function (Builder $userQuery) use ($user) {
                    $userQuery
                        ->where('users.id', $user->id)
                        ->where(function (Builder $b) {
                            $b->where('cms_page_user.can_edit', true)
                                ->orWhere('cms_page_user.can_approve', true);
                        });
                })
                ->whereHas('author', function (Builder $authorQuery) use ($user) {
                    $authorQuery->where('manager_id', $user->id)->orWhere('id', $user->id);
                });
        }

        // Collaborator: only own revisions
        return $query->where('created_by', $user->id);
    }
}
