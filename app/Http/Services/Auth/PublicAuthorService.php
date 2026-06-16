<?php

namespace App\Http\Services\Auth;

use App\Http\Repositories\Auth\PublicAuthorRepository;
use App\Models\User;
use App\Support\AuthorMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicAuthorService
{
    public function __construct(
        protected PublicAuthorRepository $repository
    ) {}

    public function findByAuthor(string $author): User
    {
        $userId = AuthorMap::userIdFromAuthor($author);

        if (! $userId) {
            throw new ModelNotFoundException('Autor não encontrado.');
        }

        $user = $this->repository->findById($userId);

        if (! $user) {
            throw new ModelNotFoundException('Autor não encontrado.');
        }

        return $user;
    }
}