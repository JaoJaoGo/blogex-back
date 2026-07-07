<?php

namespace App\Http\Services\Todo;

use App\Http\Repositories\Todo\TodoRepository;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? Todo::STATUS_OPEN;

        return $this->repository->create($data)->load('user');
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
        $todo = $this->find($id);

        return $this->repository->update($todo, $data)->load('user');
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
}