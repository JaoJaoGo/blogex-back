<?php

namespace App\Http\Repositories\Auth;

use App\Models\User;

class PublicAuthorRepository
{
    public function findById(int $id): ?User
    {
        return User::query()
            ->with('experiences')
            ->with('skills')
            ->find($id);
    }
}