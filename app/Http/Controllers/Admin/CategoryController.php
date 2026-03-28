<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(Request $request)
    {
        $type = $request->get('type', 'product');
        $categories = Category::ofType($type)->with('parent', 'children')
            ->roots()->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:categories,slug',
            'description'      => 'nullable|string',
            'content'          => 'nullable|string',
            'image'            => 'nullable|string',
            'icon'             => 'nullable|string',
            'parent_id'        => 'nullable|exists:categories,id',
            'type'             => 'nullable|in:product,post',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ]);
        $data['type'] ??= 'post';
        $data['slug'] ??= Str::slug($data['name']);
        $this->categoryService->create($data);
        return back()->with('success', 'Đã tạo danh mục.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
            'description'      => 'nullable|string',
            'content'          => 'nullable|string',
            'image'            => 'nullable|string',
            'icon'             => 'nullable|string',
            'parent_id'        => 'nullable|exists:categories,id',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ]);
        $this->categoryService->update($category->id, $data);
        return back()->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        // Chuyển children lên parent
        Category::where('parent_id', $category->id)
            ->update(['parent_id' => $category->parent_id]);
        $this->categoryService->delete($category->id);
        return back()->with('success', 'Đã xóa danh mục.');
    }
}
