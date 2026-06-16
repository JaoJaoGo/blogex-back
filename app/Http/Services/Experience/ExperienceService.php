<?php

namespace App\Http\Services\Experience;

use App\Http\Repositories\Experience\ExperienceRepository;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ExperienceService
{
    public function __construct(
        protected ExperienceRepository $repository
    ) {}

    public function list(User $user): Collection
    {
        return $this->repository->listForUser($user);
    }

    public function create(User $user, array $data): Experience
    {
        return DB::transaction(function () use ($user, $data) {
            if ($data['is_current'] ?? false) {
                $data['end_date'] = null;
            }

            return $this->repository->create($user, $data);
        });
    }

    public function update(User $user, int $id, array $data): Experience
    {
        return DB::transaction(function () use ($user, $id, $data) {
            $experience = $this->repository->findForUser($user, $id);

            if (! $experience) {
                throw new ModelNotFoundException('Experiência não encontrada.');
            }

            if (($data['is_current'] ?? false) === true) {
                $data['end_date'] = null;
            }

            return $this->repository->update($experience, $data);
        });
    }

    public function delete(User $user, int $id): void
    {
        DB::transaction(function () use ($user, $id) {
            $experience = $this->repository->findForUser($user, $id);

            if (! $experience) {
                throw new ModelNotFoundException('Experiência não encontrada.');
            }

            $this->repository->delete($experience);
        });
    }
}