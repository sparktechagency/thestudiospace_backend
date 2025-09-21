<?php

namespace App\Http\Requests\BusinessProfile;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg|max:512000',
        ];
    }

    public function messages(): array
    {
        return [
            'files.array' => 'The files must be an array.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.mimes' => 'Files must be of type jpeg, png, jpg',
            'files.*.max' => 'Each file must not exceed 500MB in size.',
        ];
    }
}
