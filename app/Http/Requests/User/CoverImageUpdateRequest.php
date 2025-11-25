<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CoverImageUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5000', // max 5MB
        ];
    }

    /**
     * Custom messages for validation
     */
    public function messages(): array
    {
        return [
            'cover_picture.required' => 'Cover image is required.',
            'cover_picture.image'    => 'The file must be an image.',
            'cover_picture.mimes'    => 'Allowed image types: jpeg, png, jpg, gif, webp.',
            'cover_picture.max'      => 'Maximum allowed image size is 5MB.',
        ];
    }
}
