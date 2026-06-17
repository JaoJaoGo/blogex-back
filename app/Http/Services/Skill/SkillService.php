<?php

namespace App\Http\Services\Skill;

use App\Http\Repositories\Skill\SkillRepository;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SkillService
{
    public function __construct(
        protected SkillRepository $repository
    ) {}

    public function list(User $user): Collection
    {
        return $this->repository->listForUser($user);
    }

    public function create(User $user, array $data): Skill
    {
        return DB::transaction(function () use ($user, $data) {
            if ($data['is_current'] ?? false) {
                $data['end_date'] = null;
            }

            return $this->repository->create($user, $data);
        });
    }

    public function update(User $user, int $id, array $data): Skill
    {
        return DB::transaction(function () use ($user, $id, $data) {
            $skill = $this->repository->findForUser($user, $id);

            if (! $skill) {
                throw new ModelNotFoundException('Habilidade não encontrada.');
            }

            if (($data['is_current'] ?? false) === true) {
                $data['end_date'] = null;
            }

            return $this->repository->update($skill, $data);
        });
    }

    public function delete(User $user, int $id): void
    {
        DB::transaction(function () use ($user, $id) {
            $skill = $this->repository->findForUser($user, $id);

            if (! $skill) {
                throw new ModelNotFoundException('Habilidade não encontrada.');
            }

            $this->repository->delete($skill);
        });
    }
}