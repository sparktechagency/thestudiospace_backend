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
            'files' => 'required|array|min:1|max:9',
            'files.*' => 'file|mimes:jpg,jpeg,png,mp4,avi,mov,webm|max:512000', //500 MB
        ];
    }
   public function messages(): array
    {
        return [
            'files.required' => 'At least one file is required.',
            'files.array' => 'Files should be in an array format.',
            'files.min' => 'At least one file is required.',
            'files.max' => 'You can upload up to 9 files only.',
            'files.*.file' => 'Each item must be a valid file.',
            'files.*.mimes' => 'Only image files (jpg, jpeg, png) and video files (mp4, avi, mov, webm) are allowed.',
            'files.*.size' => 'Each file must be less than 50MB.',
        ];
    }


}
