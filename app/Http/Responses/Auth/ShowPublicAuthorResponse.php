<?php

namespace App\Http\Responses\Auth;

use App\Http\Resources\Auth\PublicAuthorResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ShowPublicAuthorResponse
{
    public static function fromModel(User $user): JsonResponse
    {
        return response()->json([
            'data' => PublicAuthorResource::make($user),
        ]);
    }
}