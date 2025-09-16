<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserEducationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
   public function rules()
    {
        return [
            'school_or_university' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }
    public function messages()
    {
        return [
            'school_or_university.required' => 'The school or university name is required.',
            'degree.required' => 'The degree is required.',
            'start_date.required' => 'The start date is required.',
            'end_date.after' => 'The end date must be after the start date.',
        ];
    }
}
