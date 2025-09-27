<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        return [
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|string|max:1000',
        ];
    }
    public function messages()
    {
        return [
            'resume.mimes' => 'The resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'The resume cannot exceed 10MB.',
            'cover_letter.max' => 'The cover letter cannot exceed 1000 characters.',
        ];
    }
}
