<?php

namespace App\Http\Repositories\Todo;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoRepository
{
    public function listForUser(int $userId, array $filters = []): Collection
    {
        return Todo::query()
            ->with(['user', 'checklists'])
            ->where('user_id', $userId)
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['priority'] ?? null,
                fn ($query, $priority) => $query->where('priority', $priority)
            )
            ->orderByRaw("
                CASE status
                    WHEN 'open' THEN 1
                    WHEN 'in_progress' THEN 2
                    WHEN 'done' THEN 3
                    ELSE 4
                END
            ")
            ->orderByRaw("
                CASE priority
                    WHEN 'urgent' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    WHEN 'low' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('due_at')
            ->orderByDesc('updated_at')
            ->get();
    }

    public function findById(int $id): ?Todo
    {
        return Todo::query()
            ->with(['user', 'checklists'])
            ->find($id);
    }

    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);

        return $todo;
    }

    public function delete(Todo $todo): void
    {
        $todo->delete();
    }
}