<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExperienceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'workplace' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'is_current' => ['required', 'boolean'],

            'end_date' => [
                Rule::requiredIf(fn () => !$this->boolean('is_current')),
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'description' => ['nullable', 'string'],
        ];
    }
}