<?php

namespace App\Http\Resources\Todo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'priority' => $this->priority,
            'status' => $this->status,
            'startsAt' => $this->starts_at?->toISOString(),
            'dueAt' => $this->due_at?->toISOString(),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
        ];
    }
}
