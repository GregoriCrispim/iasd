<?php

namespace App\Support;

use App\Models\CmsBlock;
use App\Models\CmsPage;
use App\Models\CmsRevision;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;

class Cms
{
    /**
     * Render the published HTML for a block in the current route/page.
     */
    public static function block(string $blockKey, string $fallbackHtml = ''): HtmlString
    {
        static $pageCache = [];
        static $blockCache = [];
        static $revisionCache = [];

        $routeName = request()->attributes->get('cms_route_name') ?? Route::currentRouteName();

        if (!is_string($routeName) || $routeName === '') {
            return new HtmlString($fallbackHtml);
        }

        $page = $pageCache[$routeName] ??= CmsPage::query()->where('route_name', $routeName)->first();

        if (!$page || !$page->cms_enabled) {
            return new HtmlString($fallbackHtml);
        }

        $blockCacheKey = "{$page->id}:{$blockKey}";
        $block = $blockCache[$blockCacheKey] ??= CmsBlock::query()
            ->where('cms_page_id', $page->id)
            ->where('block_key', $blockKey)
            ->first();

        if (!$block || !$block->published_revision_id) {
            // Allow preview to show content even if not published yet.
            $previewRevisionId = request()->attributes->get('cms_preview_revision_id');
            if (is_numeric($previewRevisionId)) {
                $previewRevision = CmsRevision::query()->where('id', (int) $previewRevisionId)->first();

                if ($previewRevision && $previewRevision->cms_block_id === $block?->id) {
                    return new HtmlString($previewRevision->html);
                }
            }

            return new HtmlString($fallbackHtml);
        }

        $previewRevisionId = request()->attributes->get('cms_preview_revision_id');
        if (is_numeric($previewRevisionId)) {
            $previewRevision = CmsRevision::query()->where('id', (int) $previewRevisionId)->first();

            if ($previewRevision && $previewRevision->cms_block_id === $block->id) {
                return new HtmlString($previewRevision->html);
            }
        }

        $revision = $revisionCache[$block->published_revision_id] ??= CmsRevision::query()
            ->where('id', $block->published_revision_id)
            ->first();

        if (!$revision) {
            return new HtmlString($fallbackHtml);
        }

        return new HtmlString($revision->html);
    }
}

