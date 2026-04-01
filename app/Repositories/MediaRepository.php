<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MediaRepository
{
    public function __construct(protected Media $model) {}

    public function paginate(int $perPage = 40, ?int $folderId = null): LengthAwarePaginator
    {
        $q = $this->model->latest();
        if ($folderId !== null) {
            $q->where('folder_id', $folderId);
        } else {
            $q->whereNull('folder_id');
        }
        return $q->paginate($perPage);
    }

    public function getFolderCounts(): Collection
    {
        return $this->model->selectRaw('folder, count(*) as total')
            ->groupBy('folder')
            ->pluck('total', 'folder');
    }

    public function find(int $id): ?Media
    {
        return $this->model->find($id);
    }

    public function getForPicker(?int $folderId, string $search): \Illuminate\Support\Collection
    {
        $q = $this->model->latest()->limit(120);
        
        if ($folderId !== null) {
            $q->where('folder_id', $folderId);
        } else {
            $q->whereNull('folder_id');
        }

        if ($search !== '') {
            $q->where('name', 'like', '%' . $search . '%');
        }
        return $q->get();
    }

    public function create(array $data): Media
    {
        return $this->model->create($data);
    }

    public function moveToFolder(array $ids, ?int $folderId): int
    {
        return $this->model->whereIn('id', $ids)->update(['folder_id' => $folderId]);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function deleteMany(array $ids): int
    {
        $items = $this->model->whereIn('id', $ids)->get();
        foreach ($items as $item) {
            \Illuminate\Support\Facades\Storage::disk($item->disk)->delete($item->path);
        }
        return $this->model->whereIn('id', $ids)->delete();
    }
}
