<?php

namespace App\Http\Services\Post;

use App\Models\Post;
use App\Models\Tag;
use App\Http\Repositories\Post\PostRepository;
use App\Http\Services\Post\PostContentImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostService
{
    public function __construct(
        protected PostRepository $repository,
        protected PostContentImageService $postContentImageService,
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }

    public function find(int $id): Post|ModelNotFoundException
    {
        $post = $this->repository->findById($id);

        if (!$post) {
            throw new ModelNotFoundException('Post não encontrado.');
        }

        return $post;
    }

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $tags = $data['tags'];
            unset($data['tags']);

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $this->storeImage($data['image']);
            }

            $post = $this->repository->create($data);

            $tagIds = collect($tags)->map(function (string $name) {
                return Tag::firstOrCreate(['name' => $name])->id;
            });

            $post->tags()->sync($tagIds);

            return $post->load('tags');
        });
    }

    public function update(int $id, array $data): Post
    {
        return DB::transaction(function () use ($id, $data) {
            $post = $this->repository->findById($id);

            if (!$post) {
                throw new ModelNotFoundException('Post não encontrado.');
            }

            $oldContent = $post->content;

            if (isset($data['tags'])) {
                $tagIds = collect($data['tags'])->map(function (string $name) {
                    return Tag::firstOrCreate(['name' => $name])->id;
                });

                $post->tags()->sync($tagIds);
                unset($data['tags']);
            }

            if (!empty($data['remove_image']) && $post->image) {
                Storage::disk('public')->delete($post->image);
                $data['image'] = null;
            }

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }

                $data['image'] = $this->storeImage($data['image']);
            }

            unset($data['remove_image']);

            $this->repository->update($post, $data);

            $post->refresh();

            $this->postContentImageService->deleteRemovedImages(
                oldContent: $oldContent,
                newContent: $post->content,
                postId: $post->id,
            );

            return $post->load('tags');
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $post = $this->repository->findById($id);

            if (!$post) {
                throw new ModelNotFoundException('Post não encontrado.');
            }

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $this->postContentImageService->deleteAllFromContent(
                content: $post->content,
                postId: $post->id,
            );

            $post->tags()->detach();

            $this->repository->delete($post);
        });
    }

    protected function storeImage(UploadedFile $file): string
    {
        return $file->store('posts', 'public');
    }
}