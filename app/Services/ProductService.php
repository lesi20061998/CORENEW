<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->repository->all();
    }

    public function getPaginatedProducts(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function getProduct(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->repository->findBySlug($slug);
    }

    public function createProduct(array $data): Product
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }

        $product = $this->repository->create($data);

        // Sync attributes if provided
        if (isset($data['attributes']) && is_array($data['attributes'])) {
            $this->repository->syncAttributes($product, $data['attributes']);
        }

        return $product->load(['attributeValues.attribute']);
    }

    public function updateProduct(int $id, array $data): bool
    {
        // Generate new slug if name changed
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
        }

        $result = $this->repository->update($id, $data);

        // Sync attributes if provided
        if (isset($data['attributes']) && is_array($data['attributes'])) {
            $product = $this->repository->find($id);
            if ($product) {
                $this->repository->syncAttributes($product, $data['attributes']);
            }
        }

        return $result;
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTrashedProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateTrashed($perPage);
    }

    public function restoreProduct(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function forceDeleteProduct(int $id): bool
    {
        return $this->repository->forceDelete($id);
    }

    public function getCounts(): array
    {
        return [
            'all'     => $this->repository->countActive(),
            'trashed' => $this->repository->countTrashed(),
        ];
    }

    public function syncAttributes(Product $product, array $attributes): void
    {
        $this->repository->syncAttributes($product, $attributes);
    }

    public function getActiveProducts(): Collection
    {
        return $this->repository->getActiveProducts();
    }

    public function getFilteredProducts(array $filters = [], int $perPage = 12, ?int $priceMin = null, ?int $priceMax = null): LengthAwarePaginator
    {
        return $this->repository->getFilteredProducts($filters, $perPage, $priceMin, $priceMax);
    }

    protected function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Product::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}