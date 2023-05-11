<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'task' => 'required|string|max:256',
            'done' => 'boolean',
            'image' => 'image|mimes:jpeg,png,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'task' => 'Please enter a task'
        ];
    }
}
