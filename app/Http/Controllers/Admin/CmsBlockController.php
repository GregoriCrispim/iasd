<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsBlock;
use App\Models\CmsPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsBlockController extends Controller
{
    public function index(Request $request): View
    {
        $blocks = CmsBlock::query()
            ->with('page')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('block_key', 'like', $term)
                        ->orWhere('label', 'like', $term)
                        ->orWhereHas('page', fn ($p) => $p->where('label', 'like', $term));
                });
            })
            ->orderBy('cms_page_id')
            ->orderBy('block_key')
            ->paginate(30)
            ->withQueryString();

        return view('admin.blocks.index', ['blocks' => $blocks]);
    }

    public function create(): View
    {
        return view('admin.blocks.form', [
            'block' => new CmsBlock(),
            'pages' => $this->pageOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, null);

        CmsBlock::create([
            'cms_page_id' => $data['cms_page_id'],
            'block_key' => $data['block_key'],
            'label' => $data['label'],
        ]);

        return redirect()->route('admin.blocks.index')->with('success', 'Bloco criado.');
    }

    public function edit(CmsBlock $block): View
    {
        return view('admin.blocks.form', [
            'block' => $block,
            'pages' => $this->pageOptions(),
        ]);
    }

    public function update(Request $request, CmsBlock $block): RedirectResponse
    {
        $data = $this->validateData($request, $block);

        $block->update([
            'cms_page_id' => $data['cms_page_id'],
            'block_key' => $data['block_key'],
            'label' => $data['label'],
        ]);

        return redirect()->route('admin.blocks.index')->with('success', 'Bloco atualizado.');
    }

    public function destroy(CmsBlock $block): RedirectResponse
    {
        $block->delete();

        return redirect()->route('admin.blocks.index')->with('success', 'Bloco removido.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateData(Request $request, ?CmsBlock $block): array
    {
        return $request->validate([
            'cms_page_id' => ['required', 'exists:cms_pages,id'],
            'block_key' => [
                'required', 'string', 'max:255',
                Rule::unique('cms_blocks', 'block_key')
                    ->where(fn ($q) => $q->where('cms_page_id', $request->input('cms_page_id')))
                    ->ignore($block?->id),
            ],
            'label' => ['required', 'string', 'max:255'],
        ], [], [
            'cms_page_id' => 'página',
            'block_key' => 'chave do bloco',
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, CmsPage>
     */
    protected function pageOptions()
    {
        return CmsPage::query()->orderBy('label')->get(['id', 'label']);
    }
}
