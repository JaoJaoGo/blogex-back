<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateUserPasswordRequest;
use App\Http\Requests\Auth\UpdateUserProfileRequest;
use App\Http\Resources\Auth\UserResource;
use App\Http\Services\Auth\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $user = $this->profileService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user' => UserResource::make($user),
        ]);
    }

    public function updatePassword(UpdateUserPasswordRequest $request): JsonResponse
    {
        $this->profileService->updatePassword(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Senha atualizada com sucesso.',
        ]);
    }
}