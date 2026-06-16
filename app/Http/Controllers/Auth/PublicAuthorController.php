<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\Auth\ShowPublicAuthorResponse;
use App\Http\Services\Auth\PublicAuthorService;
use Illuminate\Http\JsonResponse;

class PublicAuthorController extends Controller
{
    public function __construct(
        protected PublicAuthorService $publicAuthorService
    ) {}

    public function show(string $author): JsonResponse
    {
        $user = $this->publicAuthorService->findByAuthor($author);

        return ShowPublicAuthorResponse::fromModel($user);
    }
}