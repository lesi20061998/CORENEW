<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Support\Str;

class PostService
{
    public function __construct(protected PostRepository $repository) {}

    public function getPaginated(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->repository->findBySlug($slug);
    }

    public function create(array $data): Post
    {
        $data['slug'] ??= $this->uniqueSlug($data['title']);
        $data['author_id'] ??= auth()->id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $postData = array_diff_key($data, ['tags' => 1]);
        $post = $this->repository->create($postData);
        $this->syncTags($post, $data['tags'] ?? []);

        return $post;
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['title'], $id);
        }
        if (isset($data['status']) && $data['status'] === 'published') {
            $data['published_at'] ??= now();
        }

        $postData = array_diff_key($data, ['tags' => 1]);
        $result = $this->repository->update($id, $postData);

        if (isset($data['tags'])) {
            $post = $this->repository->find($id);
            $this->syncTags($post, $data['tags']);
        }

        return $result;
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getPublished(int $perPage = 10)
    {
        return $this->repository->getPublished($perPage);
    }

    public function getFeatured(int $limit = 5)
    {
        return $this->repository->getFeatured($limit);
    }

    public function getTrashed(int $perPage = 15)
    {
        return $this->repository->paginateTrashed($perPage);
    }

    public function restore(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDelete(int $id)
    {
        return $this->repository->forceDelete($id);
    }

    public function getCounts()
    {
        return [
            'all'     => $this->repository->countActive(),
            'trashed' => $this->repository->countTrashed(),
        ];
    }

    private function syncTags(Post $post, array $tagNames): void
    {
        $tagIds = collect($tagNames)->filter()->map(function ($name) {
            return \App\Models\Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            )->id;
        });
        $post->tags()->sync($tagIds);
    }

    private function uniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;
        while (Post::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
