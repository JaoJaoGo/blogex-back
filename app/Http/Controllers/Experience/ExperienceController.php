<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreExperienceRequest;
use App\Http\Requests\Auth\UpdateExperienceRequest;
use App\Http\Resources\Experience\ExperienceResource;
use App\Http\Services\Experience\ExperienceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function __construct(
        protected ExperienceService $experienceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => ExperienceResource::collection(
                $this->experienceService->list($request->user())
            ),
        ]);
    }

    public function store(StoreExperienceRequest $request): JsonResponse
    {
        $experience = $this->experienceService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Experiência criada com sucesso.',
            'data' => ExperienceResource::make($experience),
        ], 201);
    }

    public function update(UpdateExperienceRequest $request, int $id): JsonResponse
    {
        $experience = $this->experienceService->update(
            $request->user(),
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Experiência atualizada com sucesso.',
            'data' => ExperienceResource::make($experience),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->experienceService->delete($request->user(), $id);

        return response()->json([
            'message' => 'Experiência removida com sucesso.',
        ]);
    }
}