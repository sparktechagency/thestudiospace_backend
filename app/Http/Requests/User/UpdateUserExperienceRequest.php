<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserExperienceRequest extends FormRequest
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
            'job_title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current' => 'boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'job_title.required' => 'Job Title is required.',
            'company.required' => 'Company name is required.',
            'location.required' => 'Location is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.before_or_equal' => 'Start date must be before or equal to end date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'current.boolean' => 'Current must be true or false.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }
}
