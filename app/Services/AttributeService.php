<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Repositories\AttributeRepository;
use Illuminate\Database\Eloquent\Collection;

class AttributeService
{
    public function __construct(
        protected AttributeRepository $repository
    ) {}

    public function getAllAttributes(): Collection
    {
        return $this->repository->all();
    }

    public function getFilterableAttributes(): Collection
    {
        return $this->repository->getFilterable();
    }

    public function getAttribute(int $id): ?Attribute
    {
        return $this->repository->find($id);
    }

    public function createAttribute(array $data): Attribute
    {
        return $this->repository->create($data);
    }

    public function updateAttribute(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function deleteAttribute(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function createAttributeValue(int $attributeId, array $data): AttributeValue
    {
        return $this->repository->createValue($attributeId, $data);
    }

    public function updateAttributeValue(int $valueId, array $data): bool
    {
        return $this->repository->updateValue($valueId, $data);
    }

    public function deleteAttributeValue(int $valueId): bool
    {
        return $this->repository->deleteValue($valueId);
    }

    public function getAvailableFilters(): Collection
    {
        return $this->repository->getAvailableFilters();
    }

    public function createAttributeWithValues(array $data): Attribute
    {
        $attribute = $this->repository->create([
            'name' => $data['name'],
            'type' => $data['type'] ?? 'select',
            'is_filterable' => $data['is_filterable'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0
        ]);

        if (isset($data['values']) && is_array($data['values'])) {
            foreach ($data['values'] as $index => $valueData) {
                $this->repository->createValue($attribute->id, [
                    'value' => $valueData['value'],
                    'color_code' => $valueData['color_code'] ?? null,
                    'sort_order' => $index
                ]);
            }
        }

        return $attribute->load('values');
    }
}