<?php

namespace App\Support;

use App\Models\CmsApproval;
use App\Models\CmsRevision;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CmsWorkflow
{
    /**
     * Revisions currently waiting on the given user's approval decision.
     */
    public static function pendingApprovalsQuery(User $user): Builder
    {
        $query = CmsRevision::query()->with(['block.page', 'author']);

        if ($user->isSuperAdmin()) {
            return $query->where('status', CmsRevision::STATUS_PENDING_SUPER_ADMIN);
        }

        if ($user->isManager()) {
            return $query
                ->where('status', CmsRevision::STATUS_PENDING_MANAGER)
                ->whereHas('author', fn (Builder $a) => $a->where('manager_id', $user->id))
                ->whereHas('block.page.users', function (Builder $u) use ($user) {
                    $u->where('users.id', $user->id)->where('cms_page_user.can_approve', true);
                });
        }

        return $query->whereRaw('1 = 0');
    }

    public static function pendingApprovalsCount(User $user): int
    {
        if (!$user->hasAnyRoleName(['super_admin', 'manager'])) {
            return 0;
        }

        return self::pendingApprovalsQuery($user)->count();
    }

    /**
     * Whether the given user may edit the revision (open the editor).
     */
    public static function canEdit(User $user, CmsRevision $revision): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($revision->created_by !== $user->id) {
            return false;
        }

        return in_array($revision->status, [CmsRevision::STATUS_DRAFT, CmsRevision::STATUS_REJECTED], true);
    }

    /**
     * Whether the given user may submit the revision for approval.
     */
    public static function canSubmit(User $user, CmsRevision $revision): bool
    {
        if (!in_array($revision->status, [CmsRevision::STATUS_DRAFT, CmsRevision::STATUS_REJECTED], true)) {
            return false;
        }

        $routeName = $revision->block?->page?->route_name;
        if (!is_string($routeName) || $routeName === '') {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $revision->created_by === $user->id && $user->canEditPage($routeName);
    }

    /**
     * Submit a revision. Super admins publish immediately.
     */
    public static function submit(User $user, CmsRevision $revision): void
    {
        if ($user->isSuperAdmin()) {
            self::publish($user, $revision, submittedNow: true);
            return;
        }

        $nextStatus = $user->isManager()
            ? CmsRevision::STATUS_PENDING_SUPER_ADMIN
            : CmsRevision::STATUS_PENDING_MANAGER;

        $revision->update([
            'status' => $nextStatus,
            'submitted_at' => now(),
        ]);
    }

    public static function canApproveAsManager(User $user, CmsRevision $revision): bool
    {
        if (!$user->isManager()) {
            return false;
        }

        if ($revision->status !== CmsRevision::STATUS_PENDING_MANAGER) {
            return false;
        }

        $page = $revision->block?->page;
        if (!$page || !$user->canApprovePage($page->route_name)) {
            return false;
        }

        return $revision->author?->manager_id === $user->id;
    }

    public static function approveAsManager(User $user, CmsRevision $revision): void
    {
        CmsApproval::create([
            'cms_revision_id' => $revision->id,
            'approver_id' => $user->id,
            'stage' => 'manager',
            'decision' => 'approved',
            'comment' => null,
        ]);

        $revision->update(['status' => CmsRevision::STATUS_PENDING_SUPER_ADMIN]);
    }

    public static function canApproveAsSuperAdmin(User $user, CmsRevision $revision): bool
    {
        return $user->isSuperAdmin() && $revision->status === CmsRevision::STATUS_PENDING_SUPER_ADMIN;
    }

    public static function approveAsSuperAdmin(User $user, CmsRevision $revision): void
    {
        self::publish($user, $revision, submittedNow: false);
    }

    public static function reject(User $user, CmsRevision $revision, ?string $comment): void
    {
        $stage = $user->isSuperAdmin() ? 'super_admin' : 'manager';

        CmsApproval::create([
            'cms_revision_id' => $revision->id,
            'approver_id' => $user->id,
            'stage' => $stage,
            'decision' => 'rejected',
            'comment' => $comment,
        ]);

        $revision->update(['status' => CmsRevision::STATUS_REJECTED]);
    }

    protected static function publish(User $user, CmsRevision $revision, bool $submittedNow): void
    {
        CmsApproval::create([
            'cms_revision_id' => $revision->id,
            'approver_id' => $user->id,
            'stage' => 'super_admin',
            'decision' => 'approved',
            'comment' => null,
        ]);

        $revision->block()->update(['published_revision_id' => $revision->id]);

        $revision->update([
            'status' => CmsRevision::STATUS_PUBLISHED,
            'submitted_at' => $submittedNow ? now() : ($revision->submitted_at ?? now()),
            'published_at' => now(),
        ]);
    }

    /**
     * Human-readable status label + badge class.
     *
     * @return array{label:string,class:string}
     */
    public static function statusMeta(string $status): array
    {
        return match ($status) {
            CmsRevision::STATUS_DRAFT => ['label' => 'Rascunho', 'class' => 'badge-gray'],
            CmsRevision::STATUS_PENDING_MANAGER => ['label' => 'Aguardando gestor', 'class' => 'badge-amber'],
            CmsRevision::STATUS_PENDING_SUPER_ADMIN => ['label' => 'Aguardando super-admin', 'class' => 'badge-blue'],
            CmsRevision::STATUS_REJECTED => ['label' => 'Rejeitado', 'class' => 'badge-red'],
            CmsRevision::STATUS_PUBLISHED => ['label' => 'Publicado', 'class' => 'badge-green'],
            default => ['label' => $status, 'class' => 'badge-gray'],
        };
    }
}
