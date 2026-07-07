<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\ListTodosRequest;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoStatusRequest;
use App\Http\Resources\Todo\TodoResource;
use App\Http\Services\Todo\TodoService;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    public function __construct(
        private readonly TodoService $todoService
    ) {
    }

    public function index(ListTodosRequest $request): JsonResponse
    {
        return response()->json([
            'data' => TodoResource::collection(
                $this->todoService->list($request->validated())
            ),
        ]);
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        return response()->json([
            'data' => new TodoResource(
                $this->todoService->create($request->validated())
            ),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => new TodoResource(
                $this->todoService->find($id)
            ),
        ]);
    }

    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'data' => new TodoResource(
                $this->todoService->update($id, $request->validated())
            ),
        ]);
    }

    public function updateStatus(UpdateTodoStatusRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'data' => new TodoResource(
                $this->todoService->updateStatus(
                    id: $id,
                    status: $request->validated('status')
                )
            ),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->todoService->delete($id);

        return response()->json(status: 204);
    }
}