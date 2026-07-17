<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsRevision;
use App\Models\User;
use App\Support\CmsWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalsController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $revisions = CmsWorkflow::pendingApprovalsQuery($user)
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.approvals.index', [
            'revisions' => $revisions,
            'user' => $user,
        ]);
    }

    public function approve(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (CmsWorkflow::canApproveAsManager($user, $revision)) {
            CmsWorkflow::approveAsManager($user, $revision);
            return back()->with('success', 'Aprovado e encaminhado ao super-admin.');
        }

        if (CmsWorkflow::canApproveAsSuperAdmin($user, $revision)) {
            CmsWorkflow::approveAsSuperAdmin($user, $revision);
            return back()->with('success', 'Revisão publicada.');
        }

        abort(403);
    }

    public function reject(Request $request, CmsRevision $revision): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!CmsWorkflow::canApproveAsManager($user, $revision) && !CmsWorkflow::canApproveAsSuperAdmin($user, $revision)) {
            abort(403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ], [], ['comment' => 'motivo']);

        CmsWorkflow::reject($user, $revision, $data['comment']);

        return back()->with('success', 'Revisão rejeitada.');
    }
}
