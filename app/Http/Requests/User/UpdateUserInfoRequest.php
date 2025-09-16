<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
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
            'cover_picture'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'job_title'          => 'nullable|string|max:255',
            'company_name'       => 'nullable|string|max:255',
            'location'           => 'nullable|string|max:255',
            'phone_number'       => 'nullable|string|max:20',
            'address'            => 'nullable|string|max:255',
            'website'            => 'nullable|url|max:255',
            'bio'                => 'nullable|string',
            'profile_visibility' => 'nullable|in:Public,Connected,Private',
        ];
    }
    public function messages(): array
    {
        return [
            'cover_picture.image'      => 'The cover picture must be an image.',
            'cover_picture.mimes'      => 'The cover picture must be a file of type: jpeg, png, jpg, gif, webp.',
            'cover_picture.max'        => 'The cover picture may not be greater than 20MB.',
            'job_title.max'            => 'Job title may not exceed 255 characters.',
            'company_name.max'         => 'Company name may not exceed 255 characters.',
            'location.max'             => 'Location may not exceed 255 characters.',
            'phone_number.max'         => 'Phone number may not exceed 20 characters.',
            'phone_number.regex'       => 'Phone number format is invalid.',
            'address.max'              => 'Address may not exceed 255 characters.',
            'website.url'              => 'Website must be a valid URL.',
            'website.max'              => 'Website may not exceed 255 characters.',
            'bio.max'                  => 'Bio may not exceed 2000 characters.',
            'profile_visibility.in'    => 'Profile visibility must be Public, Connected, or Private.',
        ];
    }

}
