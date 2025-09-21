<?php

namespace App\Http\Requests\BusinessProfile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'cover_picture' => 'nullable|image|mimes:png,jpg,jpeg|max:51200',  // Allow 50 MB images
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:51200',         // Allow 50 MB images
            'business_name' => 'required|string|max:255',
            'art_id' => 'required|exists:arts,id',  // Ensure the art_id exists in the arts table
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'social_links' => 'nullable',
            'privacy_settings' => 'required|in:public,private',  // Ensure it's either public or private
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'business_name.required' => 'Please provide a business name.',
            'business_name.string' => 'The business name must be a valid string.',
            'business_name.max' => 'The business name should not exceed 255 characters.',
            'art_id.required' => 'The art ID is required.',
            'art_id.exists' => 'The selected art ID does not exist.',
            'location.string' => 'The location must be a valid string.',
            'location.max' => 'The location should not exceed 255 characters.',
            'description.string' => 'The description must be a valid string.',
            'website.url' => 'The website must be a valid URL.',
            'email.email' => 'The email must be a valid email address.',
            'privacy_settings.required' => 'Please select the privacy setting.',
            'privacy_settings.in' => 'The privacy setting must be either public or private.',
            // Add custom messages for other fields as needed
        ];
    }
}
