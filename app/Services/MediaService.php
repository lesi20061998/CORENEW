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

    public function getPaginated(int $perPage = 40, ?int $folderId = null)
    {
        return $this->repository->paginate($perPage, $folderId);
    }

    public function getFolderCounts(): array
    {
        return $this->repository->getFolderCounts()->toArray();
    }

    public function upload(UploadedFile $file, ?int $folderId = null, string $disk = 'public'): Media
    {
        $name     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::slug($name) . '-' . time() . '.' . $file->getClientOriginalExtension();
        
        // Build physical folder path: media/parent/child/...
        $folderPath = 'media';
        if ($folderId) {
            $folderPath .= '/' . $this->getFolderPath($folderId);
        }
        
        $path = $file->storeAs($folderPath, $fileName, $disk);

        $data = [
            'name'        => $name,
            'file_name'   => $fileName,
            'mime_type'   => $file->getMimeType(),
            'path'        => $path,
            'disk'        => $disk,
            'size'        => $file->getSize(),
            'folder_id'   => $folderId,
            'uploaded_by' => auth()->id(),
        ];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            [$width, $height] = getimagesize($file->getRealPath());
            $data['width']  = $width;
            $data['height'] = $height;
        }

        return $this->repository->create($data);
    }

    private function getFolderPath(int $folderId): string
    {
        $folder = \App\Models\MediaFolder::find($folderId);
        if (!$folder) return '';

        $parts = [$folder->name];
        $temp = $folder;
        while ($temp->parent_id && ($temp = $temp->parent)) {
            array_unshift($parts, $temp->name);
        }

        return implode('/', array_map(fn($s) => Str::slug($s), $parts));
    }

    public function moveToFolder(array $ids, ?int $folderId): int
    {
        return $this->repository->moveToFolder($ids, $folderId);
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


}
