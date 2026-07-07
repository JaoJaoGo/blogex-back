<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostContentMediaRequest;
use App\Http\Services\Post\PostContentMediaService;
use Illuminate\Http\JsonResponse;

class PostContentMediaController extends Controller
{
    public function __construct(
        private readonly PostContentMediaService $PostContentMediaService
    ) {}

    public function store(StorePostContentMediaRequest $request): JsonResponse
    {
        $media = $this->PostContentMediaService->store(
            $request->file('media')
        );

        return response()->json([
            'data' => $media,
        ], 201);
    }
}