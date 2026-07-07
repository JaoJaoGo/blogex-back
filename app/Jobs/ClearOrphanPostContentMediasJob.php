<?php

namespace App\Jobs;

use App\Http\Services\Post\PostContentMediaService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearOrphanPostContentMediasJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    public int $uniqueFor = 3600;

    public function uniqueId(): string
    {
        return 'clear-orphan-post-content-media';
    }

    public function handle(PostContentMediaService $postContentMediaService): void
    {
        $deleted = $postContentMediaService->deleteOrphanMedia(
            minimumAgeDays: 1
        );

        Cache::forever(
            'post_content_media:last_orphan_cleanup_at',
            now()->toDateTimeString()
        );

        Log::info('Limpeza de mídias órfãs do conteúdo dos posts finalizada.', [
            'deleted' => $deleted,
        ]);
    }
}