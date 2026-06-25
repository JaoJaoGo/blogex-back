<?php

namespace App\Http\Services\Post;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DOMDocument;

class PostContentImageService
{
    public function store(UploadedFile $image): array
    {
        $path = $image->store(
            'posts/content/' . now()->format('Y/m'),
            'public'
        );

        return [
            'path' => $path,
            'url' => asset('storage/' . $path),
        ];
    }

    public function deleteRemovedImages(
        ?string $oldContent,
        ?string $newContent,
        ?int $postId = null
    ): void {
        $oldImages = collect($this->extractPublicStoragePaths($oldContent));
        $newImages = collect($this->extractPublicStoragePaths($newContent));

        $oldImages
            ->diff($newImages)
            ->unique()
            ->filter(fn(string $path) => Str::startsWith($path, 'posts/content/'))
            ->each(function (string $path) use ($postId) {
                if ($this->isUsedInAnotherPost($path, $postId)) {
                    return;
                }

                Storage::disk('public')->delete($path);
            });
    }

    public function deleteAllFromContent(?string $content, ?int $postId = null): void
    {
        collect($this->extractPublicStoragePaths($content))
            ->unique()
            ->filter(fn(string $path) => Str::startsWith($path, 'posts/content/'))
            ->each(function (string $path) use ($postId) {
                if ($this->isUsedInAnotherPost($path, $postId)) {
                    return;
                }

                Storage::disk('public')->delete($path);
            });
    }

    public function deleteOrphanImages(int $minimumAgeDays = 1): int
    {
        $deleted = 0;

        $files = Storage::disk('public')->allFiles('posts/content');

        foreach ($files as $path) {
            if (!$this->isOldEnough($path, $minimumAgeDays)) {
                continue;
            }

            if ($this->isUsedInAnyPost($path)) {
                continue;
            }

            Storage::disk('public')->delete($path);

            $deleted++;
        }

        return $deleted;
    }

    private function extractPublicStoragePaths(?string $html): array
    {
        if (!$html) {
            return [];
        }

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        $paths = [];

        foreach ($dom->getElementsByTagName('img') as $image) {
            $src = $image->getAttribute('src');

            $path = $this->extractPathFromImageSource($src);

            if ($path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    private function extractPathFromImageSource(?string $src): ?string
    {
        if (!$src) {
            return null;
        }

        $src = html_entity_decode($src);

        $path = parse_url($src, PHP_URL_PATH) ?: $src;
        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/')) {
            return Str::after($path, 'storage/');
        }

        return null;
    }

    private function isUsedInAnotherPost(string $path, ?int $postId = null): bool
    {
        return Post::query()
            ->when($postId, fn($query) => $query->whereKeyNot($postId))
            ->where('content', 'like', '%' . $path . '%')
            ->exists();
    }

    private function isOldEnough(string $path, int $minimumAgeDays): bool
    {
        $lastModified = Storage::disk('public')->lastModified($path);

        return $lastModified <= now()->subDays($minimumAgeDays)->timestamp;
    }

    private function isUsedInAnyPost(string $path): bool
    {
        $path = ltrim($path, '/');
        $storagePath = 'storage/' . $path;

        return Post::query()
            ->where(function ($query) use ($path, $storagePath) {
                $query
                    ->where('content', 'like', '%' . $path . '%')
                    ->orWhere('content', 'like', '%' . $storagePath . '%');
            })
            ->exists();
    }
}
