<?php

namespace App\Services;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function __construct(protected MediaRepository $repository) {}

    public function getPaginated(int $perPage = 24, ?string $folder = null)
    {
        return $this->repository->paginate($perPage, $folder);
    }

    public function getFolderCounts(): array
    {
        $counts = $this->repository->getFolderCounts()->toArray();
        $result = [];
        foreach (array_keys(Media::$rootFolders) as $folder) {
            $result[$folder] = $counts[$folder] ?? 0;
        }
        // Add any custom folders not in root list
        foreach ($counts as $folder => $count) {
            if (!isset($result[$folder])) {
                $result[$folder] = $count;
            }
        }
        return $result;
    }

    public function upload(UploadedFile $file, string $folder = 'Chung', string $disk = 'public'): Media
    {
        $name     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::slug($name) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('media', $fileName, $disk);

        $data = [
            'name'        => $name,
            'file_name'   => $fileName,
            'mime_type'   => $file->getMimeType(),
            'path'        => $path,
            'disk'        => $disk,
            'size'        => $file->getSize(),
            'folder'      => $folder,
            'uploaded_by' => auth()->id(),
        ];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            [$width, $height] = getimagesize($file->getRealPath());
            $data['width']  = $width;
            $data['height'] = $height;
        }

        return $this->repository->create($data);
    }

    public function moveToFolder(array $ids, string $folder): int
    {
        return $this->repository->moveToFolder($ids, $folder);
    }

    public function deleteMany(array $ids): int
    {
        return $this->repository->deleteMany($ids);
    }

    public function delete(int $id): bool
    {
        $media = $this->repository->find($id);
        if (!$media) return false;
        Storage::disk($media->disk)->delete($media->path);
        return $this->repository->delete($id);
    }

    public function find(int $id): ?Media
    {
        return $this->repository->find($id);
    }

    public function getForPicker(?string $folder = '', string $search = ''): \Illuminate\Support\Collection
    {
        return $this->repository->getForPicker($folder, $search);
    }

    public function getAllFolders(): array
    {
        $rootKeys = array_keys(Media::$rootFolders);
        $dbFolders = $this->repository->getFolderCounts()->keys()->toArray();
        return array_values(array_unique(array_merge($rootKeys, $dbFolders)));
    }
}
