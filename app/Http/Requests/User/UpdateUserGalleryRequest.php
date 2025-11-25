<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserGalleryRequest extends FormRequest
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
            'file' => 'required|array|min:1|max:9',
            'file.*' => 'file|mimes:jpg,jpeg,png,mp4,mov|max:51200', // 50 MB = 51200 KB
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please upload at least one file.',
            'file.array' => 'The uploaded files must be in a valid array format.',
            'file.min' => 'You must upload at least one file.',
            'file.max' => 'You cannot upload more than 9 files.',
            'file.*.file' => 'Each upload must be a valid file.',
            'file.*.mimes' => 'Only JPG, JPEG, PNG, MP4, and MOV formats are allowed.',
            'file.*.max' => 'Each file may not be greater than 50MB.',
        ];
    }





}
