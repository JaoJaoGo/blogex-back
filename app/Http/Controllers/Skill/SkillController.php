<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreSkillRequest;
use App\Http\Requests\Auth\UpdateSkillRequest;
use App\Http\Resources\Skill\SkillResource;
use App\Http\Services\Skill\SkillService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(
        protected SkillService $skillService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => SkillResource::collection(
                $this->skillService->list($request->user())
            ),
        ]);
    }

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $skill = $this->skillService->create(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Habilidade criada com sucesso.',
            'data' => SkillResource::make($skill),
        ], 201);
    }

    public function update(UpdateSkillRequest $request, int $id): JsonResponse
    {
        $skill = $this->skillService->update(
            $request->user(),
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Habilidade atualizada com sucesso.',
            'data' => SkillResource::make($skill),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->skillService->delete($request->user(), $id);

        return response()->json([
            'message' => 'Habilidade removida com sucesso.',
        ]);
    }
}