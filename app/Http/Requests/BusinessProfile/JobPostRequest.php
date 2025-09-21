<?php

namespace App\Http\Requests\BusinessProfile;

use Illuminate\Foundation\Http\FormRequest;

class JobPostRequest extends FormRequest
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
            'job_title'          => 'required|string|max:255',
            'art_id'             => 'required|exists:arts,id',  // Ensure the `art_id` exists in the `arts` table
            'job_type'           => 'required|in:Full Time,Part Time,Contract,Internship',  // Enum validation
            'location'           => 'nullable|string|max:255',
            'application_deadline'=> 'nullable|date|after:today',  // Date validation
            'job_description'    => 'nullable|string',
            'required_skills'    => 'nullable|array',  // Array validation for JSON field
            'required_skills.*'  => 'nullable|string',  // Each element of `required_skills` must be a string
            'budget'             => 'nullable|numeric|min:0',
            'status'             => 'nullable|boolean',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'job_title.required'          => 'Job title is required.',
            'art_id.exists'               => 'The selected art ID is invalid.',
            'job_type.required'           => 'Job type is required.',
            'job_type.in'                 => 'Job type must be one of: Full Time, Part Time, Contract, Internship.',
            'location.string'             => 'Location must be a string.',
            'application_deadline.date'   => 'Application deadline must be a valid date.',
            'required_skills.array'       => 'Required skills must be an array.',
            'required_skills.*.string'    => 'Each required skill must be a valid string.',
            'budget.numeric'              => 'Budget must be a valid number.',
            'budget.min'                  => 'Budget must be a positive number.',
            'status.boolean'              => 'Status must be true or false.',
        ];
    }
}
