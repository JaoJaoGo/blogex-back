<?php

namespace App\Http\Resources\Experience;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'workplace' => $this->workplace,
            'start_date' => $this->start_date?->toDateString(),
            'is_current' => $this->is_current,
            'end_date' => $this->end_date?->toDateString(),
            'description' => $this->description,
        ];
    }
}