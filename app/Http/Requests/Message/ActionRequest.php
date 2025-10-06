<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => 'required|in:Pin,Mute,Block,Delete',  // Ensure 'action' is required and must be one of the valid actions
        ];
    }

    /**
     * Get the custom error messages for validation failures.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'action.required' => 'The action field is required.',
            'action.in' => 'The action must be Pin, Mute, Block, Delete.',
        ];
    }
}
