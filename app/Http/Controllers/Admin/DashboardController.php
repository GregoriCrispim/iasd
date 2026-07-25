<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsBlock;
use App\Models\CmsPage;
use App\Models\CmsRevision;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Support\CmsWorkflow;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $isCmsUser = $user->hasAnyRoleName(['super_admin', 'manager', 'collaborator']);
        $canGaleria = $user->canManageGaleria();

        $stats = [];

        if ($isCmsUser) {
            $stats['my_revisions'] = CmsRevision::query()->where('created_by', $user->id)->count();
        }

        if ($user->isSuperAdmin()) {
            $stats['pages'] = CmsPage::query()->count();
            $stats['blocks'] = CmsBlock::query()->count();
        }

        if ($user->hasAnyRoleName(['super_admin', 'manager'])) {
            $stats['pending'] = CmsWorkflow::pendingApprovalsCount($user);
        }

        if ($canGaleria) {
            $stats['albums'] = GalleryAlbum::query()->count();
            $stats['photos'] = GalleryPhoto::query()->count();
        }

        return view('admin.dashboard', [
            'stats' => $stats,
            'isSuper' => $user->isSuperAdmin(),
            'isManager' => $user->isManager(),
            'isCmsUser' => $isCmsUser,
            'canGaleria' => $canGaleria,
        ]);
    }
}
