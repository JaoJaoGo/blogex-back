<?php

namespace App\Http\Controllers\Post;

use App\Http\Requests\Post\StorePostContentImageRequest;
use App\Http\Services\Post\PostContentImageService;
use Illuminate\Http\JsonResponse;

class PostContentImageController
{
    public function __construct(
        private readonly PostContentImageService $postContentImageService
    ) {}

    public function store(StorePostContentImageRequest $request): JsonResponse
    {
        $image = $this->postContentImageService->store(
            $request->file('image')
        );

        return response()->json([
            'data' => $image,
        ], 201);
    }
}