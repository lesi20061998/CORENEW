<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAll(): Collection
    {
        return Category::with('children')->roots()->orderBy('sort_order')->get();
    }

    public function getAllCategories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Trả về danh mục dạng phẳng có indent, dùng cho dropdown.
     * Mỗi item có thêm thuộc tính `depth` và `label_indented`.
     */
    public function getCategoryTree(string $type = 'product'): Collection
    {
        $roots = Category::ofType($type)->with('children')->roots()->orderBy('sort_order')->get();
        $flat  = collect();
        $this->flattenTree($roots, $flat, 0);
        return $flat;
    }

    protected function flattenTree($items, &$flat, int $depth): void
    {
        foreach ($items as $item) {
            $item->depth           = $depth;
            $item->label_indented  = str_repeat('— ', $depth) . $item->name;
            $flat->push($item);
            if ($item->children->isNotEmpty()) {
                $this->flattenTree($item->children, $flat, $depth + 1);
            }
        }
    }

    public function getByType(string $type): EloquentCollection
    {
        return Category::active()->ofType($type)->with('children')->roots()->orderBy('sort_order')->get();
    }

    public function find(int $id): ?Category
    {
        return Category::with('children')->find($id);
    }

    public function create(array $data): Category
    {
        $data['slug'] ??= Str::slug($data['name']);
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Category::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) Category::destroy($id);
    }
}
