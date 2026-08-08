<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = CmsPage::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('route_name', 'like', $term)
                        ->orWhere('label', 'like', $term)
                        ->orWhere('view_path', 'like', $term);
                });
            })
            ->orderBy('label')
            ->paginate(30)
            ->withQueryString();

        return view('admin.pages.index', ['pages' => $pages]);
    }

    public function edit(CmsPage $page): View
    {
        return view('admin.pages.edit', ['page' => $page]);
    }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $data = $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
            'view_path' => ['nullable', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'section_slug' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'cms_enabled' => ['nullable', 'boolean'],
        ]);

        $page->update([
            'route_name' => $data['route_name'],
            'view_path' => $data['view_path'] ?? null,
            'label' => $data['label'],
            'section_slug' => $data['section_slug'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'cms_enabled' => $request->boolean('cms_enabled'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Página atualizada.');
    }

    public function sync(): RedirectResponse
    {
        Artisan::call('cms:sync-pages');

        return redirect()->route('admin.pages.index')->with('success', 'Páginas sincronizadas a partir das rotas.');
    }
}
