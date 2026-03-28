<?php

namespace App\Repositories;

use App\Models\Widget;
use Illuminate\Database\Eloquent\Collection;

class WidgetRepository
{
    public function __construct(protected Widget $model) {}

    public function all(): Collection
    {
        return $this->model->orderBy('area')->orderBy('sort_order')->get();
    }

    public function forArea(string $area): Collection
    {
        return $this->model->active()->forArea($area)->get();
    }

    public function find(int $id): ?Widget
    {
        return $this->model->find($id);
    }

    public function create(array $data): Widget
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
