<?php

namespace App\Http\Controllers\Post;

use App\Http\Requests\Post\StorePostContentImageRequest;
use App\Http\Services\Post\PostContentMediaService;
use Illuminate\Http\JsonResponse;

class PostContentImageController
{
    public function __construct(
        private readonly PostContentMediaService $PostContentMediaService
    ) {}

    public function store(StorePostContentImageRequest $request): JsonResponse
    {
        $image = $this->PostContentMediaService->store(
            $request->file('image')
        );

        return response()->json([
            'data' => $image,
        ], 201);
    }
}