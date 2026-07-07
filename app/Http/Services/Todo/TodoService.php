<?php

namespace App\Http\Services\Todo;

use App\Http\Repositories\Todo\TodoRepository;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\TodoChecklist;
use Illuminate\Support\Facades\DB;

class TodoService
{
    public function __construct(
        private readonly TodoRepository $repository
    ) {}

    public function list(array $filters = []): Collection
    {
        return $this->repository->listForUser(userId: auth()->id(), filters: $filters);
    }

    public function create(array $data): Todo
    {
        return DB::transaction(function () use ($data) {
            $checklists = $data['checklists'] ?? [];

            unset($data['checklists']);

            $data['user_id'] = auth()->id();
            $data['status'] = $data['status'] ?? Todo::STATUS_OPEN;

            $todo = $this->repository->create($data);

            $this->syncChecklists($todo, $checklists);

            return $todo->load(['user', 'checklists']);
        });
    }

    public function find(int $id): Todo
    {
        $todo = $this->repository->findById($id);

        if (!$todo) {
            throw new ModelNotFoundException('To-do não encontrado.');
        }

        $this->ensureOwner($todo);

        return $todo;
    }

    public function update(int $id, array $data): Todo
    {
        return DB::transaction(function () use ($id, $data) {
            $todo = $this->find($id);

            $checklists = $data['checklists'] ?? null;

            unset($data['checklists']);

            $this->repository->update($todo, $data);

            if (is_array($checklists)) {
                $this->syncChecklists($todo, $checklists);
            }

            return $todo->refresh()->load(['user', 'checklists']);
        });
    }

    public function updateStatus(int $id, string $status): Todo
    {
        $todo = $this->find($id);

        return $this->repository->update($todo, ['status' => $status])->load('user');
    }

    public function delete(int $id): void
    {
        $todo = $this->find($id);

        $this->repository->delete($todo);
    }

    private function ensureOwner(Todo $todo): void
    {
        if((int) $todo->user_id !== (int) auth()->id()) {
            throw new AuthorizationException('Você não tem permissão para acessar este to-do.');
        }
    }

    private function syncChecklists(Todo $todo, array $checklists): void
    {
        $receivedIds = collect($checklists)
            ->pluck('id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        $todo->checklists()
            ->when(
                $receivedIds->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $receivedIds),
                fn ($query) => $query
            )
            ->delete();

        foreach ($checklists as $index => $checklistData) {
            $payload = [
                'title' => $checklistData['title'],
                'is_completed' => (bool) ($checklistData['is_completed'] ?? false),
                'position' => $checklistData['position'] ?? $index,
            ];

            if (! empty($checklistData['id'])) {
                $checklist = $todo->checklists()
                    ->whereKey($checklistData['id'])
                    ->first();

                if ($checklist) {
                    $checklist->update($payload);
                    continue;
                }
            }

            $todo->checklists()->create($payload);
        }
    }
}