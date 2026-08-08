<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsBlock;
use App\Models\CmsRevision;
use App\Models\User;
use App\Support\CmsWorkflow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsRevisionController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $query = $this->scopedQuery($user)
            ->with(['block.page', 'author'])
            ->withCount('approvals');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $revisions = $query->latest('id')->paginate(20)->withQueryString();

        return view('admin.revisions.index', [
            'revisions' => $revisions,
            'user' => $user,
            'statuses' => $this->statusOptions(),
        ]);
    }

    public function create(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        if (!$this->canCreate($user)) {
            abort(403);
        }

        return view('admin.revisions.form', [
            'revision' => new CmsRevision(['status' => CmsRevision::STATUS_DRAFT]),
            'blocks' => $this->blockOptions($user),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!$this->canCreate($user)) {
            abort(403);
        }

        $data = $request->validate([
            'cms_block_id' => ['required', 'exists:cms_blocks,id'],
            'html' => ['required', 'string'],
        ], [], ['cms_block_id' => 'bloco', 'html' => 'conteúdo']);

        if (!$this->blockOptions($user)->has((int) $data['cms_block_id'])) {
            abort(403);
        }

        $revision = CmsRevision::create([
            'cms_block_id' => $data['cms_block_id'],
            'html' => $data['html'],
            'status' => CmsRevision::STATUS_DRAFT,
            'created_by' => $user->id,
        ]);

        return redirect()->route('admin.revisions.index')->with('success', 'Rascunho salvo. Envie para aprovação quando estiver pronto.');
    }

    public function edit(Request $request, CmsRevision $revision): View
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canEdit($user, $revision)) {
            abort(403);
        }

        $revision->load('block.page');

        return view('admin.revisions.form', [
            'revision' => $revision,
            'blocks' => $this->blockOptions($user),
        ]);
    }

    public function update(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canEdit($user, $revision)) {
            abort(403);
        }

        $data = $request->validate([
            'html' => ['required', 'string'],
        ], [], ['html' => 'conteúdo']);

        $update = ['html' => $data['html']];

        // A rejected revision goes back to draft when edited again.
        if ($revision->status === CmsRevision::STATUS_REJECTED) {
            $update['status'] = CmsRevision::STATUS_DRAFT;
        }

        $revision->update($update);

        return redirect()->route('admin.revisions.index')->with('success', 'Revisão atualizada.');
    }

    public function submit(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canSubmit($user, $revision)) {
            abort(403);
        }

        CmsWorkflow::submit($user, $revision);

        return back()->with('success', 'Revisão enviada.');
    }

    public function approveManager(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canApproveAsManager($user, $revision)) {
            abort(403);
        }

        CmsWorkflow::approveAsManager($user, $revision);

        return back()->with('success', 'Revisão aprovada e encaminhada ao super-admin.');
    }

    public function approveSuper(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canApproveAsSuperAdmin($user, $revision)) {
            abort(403);
        }

        CmsWorkflow::approveAsSuperAdmin($user, $revision);

        return back()->with('success', 'Revisão publicada.');
    }

    public function reject(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $isManagerReject = CmsWorkflow::canApproveAsManager($user, $revision);
        $isSuperReject = CmsWorkflow::canApproveAsSuperAdmin($user, $revision);

        if (!$isManagerReject && !$isSuperReject) {
            abort(403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ], [], ['comment' => 'motivo']);

        CmsWorkflow::reject($user, $revision, $data['comment']);

        return back()->with('success', 'Revisão rejeitada.');
    }

    public function destroy(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user->isSuperAdmin()) {
            abort(403);
        }

        $revision->delete();

        return redirect()->route('admin.revisions.index')->with('success', 'Revisão removida.');
    }

    protected function canCreate(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->pages()->wherePivot('can_edit', true)->exists();
    }

    protected function scopedQuery(User $user): Builder
    {
        $query = CmsRevision::query();

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

        return $query->where('created_by', $user->id);
    }

    /**
     * @return \Illuminate\Support\Collection<int, string> keyed by block id
     */
    protected function blockOptions(User $user)
    {
        $query = CmsBlock::query()
            ->with('page')
            ->whereHas('page', fn (Builder $pageQuery) => $pageQuery->where('cms_enabled', true))
            ->orderBy('cms_page_id');

        if (!$user->isSuperAdmin()) {
            $query->whereHas('page.users', function (Builder $userQuery) use ($user) {
                $userQuery
                    ->where('users.id', $user->id)
                    ->where('cms_page_user.can_edit', true);
            });
        }

        return $query->get()->mapWithKeys(fn (CmsBlock $block) => [
            $block->id => ($block->page?->label ? "{$block->page->label} — " : '') . $block->label . " ({$block->block_key})",
        ]);
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        return [
            CmsRevision::STATUS_DRAFT => 'Rascunho',
            CmsRevision::STATUS_PENDING_MANAGER => 'Aguardando gestor',
            CmsRevision::STATUS_PENDING_SUPER_ADMIN => 'Aguardando super-admin',
            CmsRevision::STATUS_REJECTED => 'Rejeitado',
            CmsRevision::STATUS_PUBLISHED => 'Publicado',
        ];
    }
}
