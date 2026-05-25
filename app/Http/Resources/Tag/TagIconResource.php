<?php

namespace App\Http\Resources\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagIconResource
 * 
 * Resource responsável pela transformação dos ícones disponíveis para tags.
 * 
 * @package App\Http\Resources\Tag
 */
class TagIconResource extends JsonResource
{
    /**
     * Transforma o resource em array.
     * 
     * @param Request $request
     * @return array<string, string>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->resource['key'],
            'label' => $this->resource['label'],
        ];
    }
}