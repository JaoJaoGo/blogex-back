<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\FetchPostLinkPreviewRequest;
use App\Http\Services\Post\PostLinkPreviewService;
use Illuminate\Http\JsonResponse;

class PostLinkPreviewController extends Controller
{
    public function __construct(
        private readonly PostLinkPreviewService $postLinkPreviewService
    ) {}

    public function show(FetchPostLinkPreviewRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->postLinkPreviewService->preview(
                $request->validated('url')
            ),
        ]);
    }
}
