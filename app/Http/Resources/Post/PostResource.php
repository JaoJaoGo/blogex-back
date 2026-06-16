<?php

namespace App\Http\Resources\Post;

use App\Models\User;
use App\Support\AuthorMap;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * Class PostResource
 *
 * Resource responsável pela transformação da entidade
 * {@see Post} em uma estrutura JSON
 * padronizada para respostas da API.
 *
 * Garante que apenas os campos necessários sejam
 * expostos ao cliente, evitando vazamento de
 * dados sensíveis e mantendo consistência
 * entre diferentes endpoints.
 *
 * @package App\Http\Resources\Post
 */
class PostResource extends JsonResource
{
    /**
     * Transforma o resource em um array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = AuthorMap::userIdFromAuthor($this->author);
        $user = $userId ? User::find($userId) : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => $this->content,
            'image' => $this->image,
            'author' => $this->author,
            'user' => [
                'id' => $user?->id,
                'name' => $user?->name,
                'profile_photo_url' => $user?->profile_photo
                    ? asset('storage/' . $user->profile_photo)
                    : null,
            ],
            'tags' => $this->whenLoaded('tags', fn() =>
                $this->tags->map(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'icon' => $tag->icon,
                    'color' => $tag->color,
                ])),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
