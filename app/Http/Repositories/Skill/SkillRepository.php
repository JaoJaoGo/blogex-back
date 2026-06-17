<?php

namespace App\Http\Repositories\Skill;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SkillRepository
{
    public function listForUser(User $user): Collection
    {
        return $user
            ->skills()
            ->get();
    }

    public function findForUser(User $user, int $id): ?Skill
    {
        return $user
            ->skills()
            ->where('id', $id)
            ->first();
    }

    public function create(User $user, array $data): Skill
    {
        return $user
            ->skills()
            ->create($data);
    }

    public function update(Skill $skill, array $data): Skill
    {
        $skill->update($data);

        return $skill;
    }

    public function delete(Skill $skill): void
    {
        $skill->delete();
    }
}