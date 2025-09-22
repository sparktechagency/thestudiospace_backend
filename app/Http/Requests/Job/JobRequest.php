<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
    public function rules()
    {
        return [
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',  // Allow only PDFs, DOCs, DOCX up to 10MB
            'cover_letter' => 'nullable|string|max:1000',  // Cover letter can be up to 1000 characters
        ];
    }

    /**
     * Customize the error messages for the validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'resume.mimes' => 'The resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'The resume cannot exceed 10MB.',
            'cover_letter.max' => 'The cover letter cannot exceed 1000 characters.',
        ];
    }
}
