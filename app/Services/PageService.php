<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Support\Str;

class PageService
{
    public function __construct(protected PageRepository $repository) {}

    public function getAll()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Page
    {
        return $this->repository->find($id);
    }

    public function findBySlug(string $slug): ?Page
    {
        return $this->repository->findBySlug($slug);
    }

    public function create(array $data): Page
    {
        $data['slug'] ??= $this->uniqueSlug($data['title']);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['title'], $id);
        }
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    private function uniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;
        while (Page::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
