<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Experience\ExperienceResource;
use App\Http\Resources\Skill\SkillResource;
use App\Support\AuthorMap;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicAuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => AuthorMap::authorFromUserId($this->id),
            'name' => $this->name,
            'age' => $this->age,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'email' => $this->email,
            'description' => $this->description,
            'profile_photo' => $this->profile_photo,
            'profile_photo_url' => $this->profile_photo
                ? asset('storage/' . $this->profile_photo)
                : null,
            'skills' => SkillResource::collection(
                $this->whenLoaded('skills')
            ),
            'experiences' => ExperienceResource::collection(
                $this->whenLoaded('experiences')
            ),
            'linkedin' => $this->linkedin,
            'github' => $this->github,
        ];
    }
}