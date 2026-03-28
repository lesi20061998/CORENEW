<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(protected PageService $pageService) {}

    public function index()
    {
        $pages = $this->pageService->getAll();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $templates = Page::availableTemplates();
        return view('admin.pages.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:pages,slug',
            'content'          => 'nullable|string',
            'template'         => 'nullable|string',
            'status'           => 'nullable|in:published,draft',
            'sort_order'       => 'integer|min:0',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'      => 'nullable|url',
            'seo_focus_keyword'  => 'nullable|string|max:255',
            'robots_meta'        => 'nullable|array',
            'schema_json'        => 'nullable',
        ]);

        $data['status'] ??= 'draft';
        $data['template'] ??= 'default';

        $this->pageService->create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(int $id)
    {
        $page      = $this->pageService->find($id) ?? abort(404);
        $templates = Page::availableTemplates();
        return view('admin.pages.edit', compact('page', 'templates'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:pages,slug,' . $id,
            'content'          => 'nullable|string',
            'template'         => 'nullable|string',
            'status'           => 'nullable|in:published,draft',
            'sort_order'       => 'integer|min:0',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'      => 'nullable|url',
            'seo_focus_keyword'  => 'nullable|string|max:255',
            'robots_meta'        => 'nullable|array',
            'schema_json'        => 'nullable',
        ]);

        $this->pageService->update($id, $data);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(int $id)
    {
        $this->pageService->delete($id);
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }
}
