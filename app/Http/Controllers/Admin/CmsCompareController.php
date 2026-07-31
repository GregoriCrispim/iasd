<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsRevision;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CmsCompareController extends Controller
{
    public function show(CmsRevision $cmsRevision): Response
    {
        $user = Auth::guard('admin')->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $page = $cmsRevision->block?->page;
        $routeName = $page?->route_name;

        if (! is_string($routeName) || $routeName === '' || ! $page?->cms_enabled) {
            abort(404);
        }

        if (! $user->isSuperAdmin()) {
            if ($user->isManager()) {
                $author = $cmsRevision->author;
                if (! $author || ($author->manager_id !== $user->id && $author->id !== $user->id)) {
                    abort(403);
                }
            }

            if (! $user->canEditPage($routeName) && ! $user->canApprovePage($routeName)) {
                abort(403);
            }
        }

        $publishedHtml = $cmsRevision->block?->publishedRevision?->html ?? '';
        $proposedHtml = $cmsRevision->html;

        return response()
            ->view('admin.cms.compare', [
                'cmsRevision' => $cmsRevision,
                'publishedUrl' => route($routeName),
                'previewUrl' => route('admin.cms.preview', $cmsRevision),
                'publishedHtml' => $publishedHtml,
                'proposedHtml' => $proposedHtml,
            ], 200)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
