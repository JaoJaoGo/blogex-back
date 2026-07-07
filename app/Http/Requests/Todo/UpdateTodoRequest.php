<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::in(Todo::priorities())],
            'status' => ['required', Rule::in(Todo::statuses())],
            'starts_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            
            'checklists' => ['sometimes', 'array'],
            'checklists.*.id' => ['nullable', 'integer'],
            'checklists.*.title' => ['required', 'string', 'max:255'],
            'checklists.*.is_completed' => ['sometimes', 'boolean'],
            'checklists.*.position' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
