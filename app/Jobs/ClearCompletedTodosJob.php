<?php

namespace App\Jobs;

use App\Models\Todo;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ClearCompletedTodosJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    public int $uniqueFor = 3600;

    public function uniqueId(): string
    {
        return 'clear-completed-todos';
    }

    public function handle(): void
    {
        $deleted = Todo::query()
            ->where('status', Todo::STATUS_DONE)
            ->where('updated_at', '<=', now()->subDays(30))
            ->delete();

        Log::info('Limpeza de to-dos concluídos finalizada.', [
            'deleted' => $deleted,
        ]);
    }
}