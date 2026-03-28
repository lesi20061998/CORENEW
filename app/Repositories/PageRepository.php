<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

class PageRepository
{
    public function __construct(protected Page $model) {}

    public function all(): Collection
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function find(int $id): ?Page
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Page
    {
        return $this->model->where('slug', $slug)->published()->firstOrFail();
    }

    public function create(array $data): Page
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }
}
