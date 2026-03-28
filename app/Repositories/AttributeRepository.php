<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Collection;

class AttributeRepository
{
    public function __construct(
        protected Attribute $model,
        protected AttributeValue $valueModel
    ) {}

    public function all(): Collection
    {
        return $this->model->with('values')->orderBy('sort_order')->get();
    }

    public function getFilterable(): Collection
    {
        return $this->model->filterable()->with('values')->get();
    }

    public function find(int $id): ?Attribute
    {
        return $this->model->with('values')->find($id);
    }

    public function create(array $data): Attribute
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

    public function createValue(int $attributeId, array $data): AttributeValue
    {
        return $this->valueModel->create(array_merge($data, [
            'attribute_id' => $attributeId
        ]));
    }

    public function updateValue(int $valueId, array $data): bool
    {
        return $this->valueModel->where('id', $valueId)->update($data);
    }

    public function deleteValue(int $valueId): bool
    {
        return $this->valueModel->destroy($valueId);
    }

    public function getAvailableFilters(): Collection
    {
        return $this->model->filterable()
                          ->with(['values' => function ($query) {
                              $query->whereHas('products', function ($q) {
                                  $q->where('status', 'active');
                              })->orderBy('sort_order');
                          }])
                          ->get()
                          ->filter(function ($attribute) {
                              return $attribute->values->isNotEmpty();
                          });
    }
}