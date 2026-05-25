<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PostListResource
 *
 * Resource responsável pela transformação
 * da entidade Post para o contexto de LISTAGEM.
 *
 * Retorna apenas os dados necessários
 * para exibição em listas, evitando
 * payloads excessivos.
 *
 * @package App\Http\Resources\Post
 */
class PostListResource extends JsonResource
{
    /**
     * Transforma o resource em array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'author' => $this->author,

            'tags' => $this->whenLoaded('tags', fn () =>
                $this->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'icon' => $tag->icon,
                    'color' => $tag->color,
                ])
            ),

            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
