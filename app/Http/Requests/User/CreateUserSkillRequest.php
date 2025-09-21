<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:user_skills,name,',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The skill name is required and cannot be left empty.',
            'name.string' => 'The skill name must be a valid string.',
            'name.max' => 'The skill name cannot exceed 255 characters.',
            'name.unique' => 'This name is already taken.',
        ];
    }
}
