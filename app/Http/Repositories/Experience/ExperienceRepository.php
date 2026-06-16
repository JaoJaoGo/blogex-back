<?php

namespace App\Http\Repositories\Experience;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExperienceRepository
{
    public function listForUser(User $user): Collection
    {
        return $user
            ->experiences()
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();
    }

    public function findForUser(User $user, int $id): ?Experience
    {
        return $user
            ->experiences()
            ->where('id', $id)
            ->first();
    }

    public function create(User $user, array $data): Experience
    {
        return $user
            ->experiences()
            ->create($data);
    }

    public function update(Experience $experience, array $data): Experience
    {
        $experience->update($data);

        return $experience;
    }

    public function delete(Experience $experience): void
    {
        $experience->delete();
    }
}