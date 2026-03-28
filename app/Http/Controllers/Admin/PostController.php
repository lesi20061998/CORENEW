<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService,
        protected CategoryService $categoryService
    ) {}

    public function index()
    {
        $posts = $this->postService->getPaginated(15);
        $counts = $this->postService->getCounts();
        return view('admin.posts.index', compact('posts', 'counts'));
    }

    public function create()
    {
        $categories = $this->categoryService->getByType('post');
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:posts,slug',
            'content'          => 'nullable|string',
            'excerpt'          => 'nullable|string',
            'thumbnail'        => 'nullable|string',
            'category_id'      => 'nullable|exists:categories,id',
            'status'           => 'nullable|in:published,draft,scheduled',
            'is_featured'      => 'boolean',
            'tags'             => 'nullable|array',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'      => 'nullable|url',
            'seo_focus_keyword'  => 'nullable|string|max:255',
            'robots_meta'        => 'nullable|array',
            'schema_json'        => 'nullable',
        ]);

        $data['status'] ??= 'draft';

        $this->postService->create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(int $id)
    {
        $post       = $this->postService->find($id) ?? abort(404);
        $categories = $this->categoryService->getByType('post');
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:posts,slug,' . $id,
            'content'          => 'nullable|string',
            'excerpt'          => 'nullable|string',
            'thumbnail'        => 'nullable|string',
            'category_id'      => 'nullable|exists:categories,id',
            'status'           => 'nullable|in:published,draft,scheduled',
            'is_featured'      => 'boolean',
            'tags'             => 'nullable|array',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'      => 'nullable|url',
            'seo_focus_keyword'  => 'nullable|string|max:255',
            'robots_meta'        => 'nullable|array',
            'schema_json'        => 'nullable',
        ]);

        $this->postService->update($id, $data);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(int $id)
    {
        $this->postService->delete($id);
        return redirect()->route('admin.posts.index')->with('success', 'Post moved to trash.');
    }

    public function trash()
    {
        $posts = $this->postService->getTrashed(15);
        $counts = $this->postService->getCounts();
        return view('admin.posts.trash', compact('posts', 'counts'));
    }

    public function restore(int $id)
    {
        $this->postService->restore($id);
        return redirect()->route('admin.posts.trash')->with('success', 'Post restored.');
    }

    public function forceDelete(int $id)
    {
        $this->postService->forceDelete($id);
        return redirect()->route('admin.posts.trash')->with('success', 'Post deleted permanently.');
    }

    public function quickUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'status'      => 'nullable|in:published,draft,scheduled',
            'is_featured' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $this->postService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nhanh thành công!',
        ]);
    }
}
