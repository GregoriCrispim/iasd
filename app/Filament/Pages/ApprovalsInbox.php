<?php

namespace App\Filament\Pages;

use App\Models\CmsApproval;
use App\Models\CmsRevision;
use App\Models\User;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ApprovalsInbox extends Page implements HasTable
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Aprovações';
    protected static ?string $navigationGroup = 'CMS';

    protected static string $view = 'filament.pages.approvals-inbox';

    use InteractsWithTable;

    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->hasAnyRoleName(['super_admin', 'manager']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getInboxQuery())
            ->columns([
                Tables\Columns\TextColumn::make('block.page.label')->label('Página')->searchable(),
                Tables\Columns\TextColumn::make('block.label')->label('Bloco')->searchable(),
                Tables\Columns\TextColumn::make('author.name')->label('Autor')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('submitted_at')->label('Enviado')->dateTime(),
            ])
            ->actions([
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

                Tables\Actions\Action::make('approve')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check')
                    ->visible(fn (CmsRevision $record) => $this->canApprove($record))
                    ->action(fn (CmsRevision $record) => $this->approve($record)),

                Tables\Actions\Action::make('reject')
                    ->label('Rejeitar')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (CmsRevision $record) => $this->canReject($record))
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Motivo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(fn (CmsRevision $record, array $data) => $this->reject($record, $data['comment'] ?? null)),
            ]);
    }

    protected function getInboxQuery(): Builder
    {
        $user = Auth::user();

        $query = CmsRevision::query()->with(['block.page', 'author']);

        if (!$user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isSuperAdmin()) {
            return $query->where('status', CmsRevision::STATUS_PENDING_SUPER_ADMIN);
        }

        // Manager: pending manager approvals from own collaborators and only for pages where can_approve.
        return $query
            ->where('status', CmsRevision::STATUS_PENDING_MANAGER)
            ->whereHas('author', fn (Builder $a) => $a->where('manager_id', $user->id))
            ->whereHas('block.page.users', function (Builder $u) use ($user) {
                $u->where('users.id', $user->id)->where('cms_page_user.can_approve', true);
            });
    }

    protected function canApprove(CmsRevision $record): bool
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return $record->status === CmsRevision::STATUS_PENDING_SUPER_ADMIN;
        }

        if ($user->isManager()) {
            return $record->status === CmsRevision::STATUS_PENDING_MANAGER
                && ($record->author?->manager_id === $user->id);
        }

        return false;
    }

    protected function canReject(CmsRevision $record): bool
    {
        return $this->canApprove($record);
    }

    protected function approve(CmsRevision $record): void
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return;
        }

        if ($user->isManager()) {
            CmsApproval::create([
                'cms_revision_id' => $record->id,
                'approver_id' => $user->id,
                'stage' => 'manager',
                'decision' => 'approved',
                'comment' => null,
            ]);

            $record->update(['status' => CmsRevision::STATUS_PENDING_SUPER_ADMIN]);
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

            $record->block()->update(['published_revision_id' => $record->id]);
            $record->update(['status' => CmsRevision::STATUS_PUBLISHED, 'published_at' => now()]);
        }
    }

    protected function reject(CmsRevision $record, ?string $comment): void
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return;
        }

        $stage = $user->isSuperAdmin() ? 'super_admin' : 'manager';

        CmsApproval::create([
            'cms_revision_id' => $record->id,
            'approver_id' => $user->id,
            'stage' => $stage,
            'decision' => 'rejected',
            'comment' => $comment,
        ]);

        $record->update(['status' => CmsRevision::STATUS_REJECTED]);
    }
}
