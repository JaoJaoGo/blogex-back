<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_current')) {
            $this->merge([
                'is_current' => filter_var(
                    $this->input('is_current'),
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
            'workplace' => ['sometimes', 'string', 'max:255'],
            'start_date' => ['sometimes', 'date'],
            'is_current' => ['sometimes', 'boolean'],

            'end_date' => [
                Rule::requiredIf(fn () => $this->has('is_current') && !$this->boolean('is_current')),
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'description' => ['nullable', 'string'],
        ];
    }
}