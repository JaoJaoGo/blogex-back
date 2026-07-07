<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', Rule::in(Todo::priorities())],
            'status' => ['sometimes', Rule::in(Todo::statuses())],
            'starts_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
