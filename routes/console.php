<?php

use App\Jobs\ClearCompletedTodosJob;
use App\Jobs\ClearOrphanPostContentImagesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new ClearOrphanPostContentImagesJob)
    ->dailyAt('03:00')
    ->name('clear-orphan-post-content-images')
    ->withoutOverlapping()
    ->when(function () {
        $lastCleanupAt = Cache::get('post_content_images:last_orphan_cleanup_at');

        if (!$lastCleanupAt) {
            return true;
        }

        return Carbon::parse($lastCleanupAt)->lte(now()->subDays(30));
    });

Schedule::job(new ClearCompletedTodosJob)
    ->dailyAt('03:30')
    ->name('clear-completed-todos')
    ->withoutOverlapping();
