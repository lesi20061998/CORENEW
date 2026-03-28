<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function __construct(protected Post $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['category', 'author'])->latest()->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return $this->model->with(['category', 'tags', 'author'])->find($id);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->model->with(['category', 'tags', 'author'])->where('slug', $slug)->firstOrFail();
    }

    public function create(array $data): Post
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

    public function getPublished(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->published()->with(['category', 'author'])->latest('published_at')->paginate($perPage);
    }

    public function getFeatured(int $limit = 5)
    {
        return $this->model->published()->featured()->with('category')->latest('published_at')->limit($limit)->get();
    }

    public function paginateTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->with(['category', 'author'])->latest('deleted_at')->paginate($perPage);
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
