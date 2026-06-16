<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('remove_profile_photo')) {
            $this->merge([
                'remove_profile_photo' => filter_var(
                    $this->input('remove_profile_photo'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'age' => ['sometimes', 'integer', 'min:1'],
            'birth_date' => ['sometimes', 'date'],
            'phone' => ['sometimes', 'string', 'max:30'],

            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],

            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'remove_profile_photo' => ['sometimes', 'boolean'],
        ];
    }
}
