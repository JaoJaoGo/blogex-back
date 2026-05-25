<?php

namespace App\Http\Responses\Tag;

use App\Http\Resources\Tag\TagIconResource;
use Illuminate\Http\JsonResponse;

/**
 * Class ListTagIconsResponse
 * 
 * Classe responsável por centralizar respostas HTTP relacionadas aos ícones disponíveis para tags.
 * 
 * @package App\Http\Responses\Tag
 */
class ListTagIconsResponse
{
    /**
     * Cria uma resposta de listagem de ícones.
     * 
     * @param array<int, array<string, string>> $icons
     * @return JsonResponse
     */
    public static function fromArray(array $icons): JsonResponse
    {
        return response()->json([
            'data' => TagIconResource::collection(collect($icons)),
        ]);
    }
}