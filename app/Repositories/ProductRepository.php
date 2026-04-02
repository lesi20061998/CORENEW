<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(
        protected Product $model
    ) {}

    public function all(): Collection
    {
        return $this->model->with(['attributeValues.attribute'])->get();
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['categories.parent'])->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        if (!empty($filters['search'])) {
            $query->where(fn($q) => $q->where('name', 'like', '%'.$filters['search'].'%')
                                      ->orWhere('sku', 'like', '%'.$filters['search'].'%'));
        }
        if (!empty($filters['category_ids'])) {
            $categoryIds = is_array($filters['category_ids']) ? $filters['category_ids'] : [$filters['category_ids']];
            $query->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Product
    {
        return $this->model->with(['attributeValues.attribute', 'combos.variants'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->with(['attributeValues.attribute'])
                          ->where('slug', $slug)
                          ->first();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function getActiveProducts(): Collection
    {
        return $this->model->active()
                          ->with(['attributeValues.attribute'])
                          ->orderBy('sort_order', 'asc')
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getFilteredProducts(array $filters = [], int $perPage = 12, ?int $priceMin = null, ?int $priceMax = null): LengthAwarePaginator
    {
        $query = $this->model->active()->with(['attributeValues.attribute']);

        if (!empty($filters)) {
            $query->withFilters($filters);
        }

        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }
        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function syncAttributes(Product $product, array $attributes): void
    {
        $product->productAttributes()->delete();

        foreach ($attributes as $attributeId => $valueIds) {
            if (is_array($valueIds)) {
                foreach ($valueIds as $valueId) {
                    $product->productAttributes()->create([
                        'attribute_id' => $attributeId,
                        'attribute_value_id' => $valueId
                    ]);
                }
            } else {
                $product->productAttributes()->create([
                    'attribute_id' => $attributeId,
                    'attribute_value_id' => $valueIds
                ]);
            }
        }
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with(['categories.parent'])->latest('deleted_at')->paginate($perPage);
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function forceDelete(int $id): bool
    {
        return $this->model->withTrashed()->find($id)->forceDelete();
    }

    public function countActive(): int
    {
        return $this->model->count();
    }

    public function countTrashed(): int
    {
        return $this->model->onlyTrashed()->count();
    }
}