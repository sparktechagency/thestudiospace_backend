<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'art_id' => 'required|exists:arts,id',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:1000',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'group_type' => 'required|in:public,private',
            'allow_post' => 'boolean',
            'admin_approval' => 'boolean',
            'member_id' => 'nullable|array|distinct',
            'member_id.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The group name is required.',
            'art_id.exists' => 'The selected art does not exist.',
            'group_type.in' => 'The group type must be either public or private.',
            'banner_image.image' => 'The banner must be a valid image file.',
            'logo_image.image' => 'The logo must be a valid image file.',
            'member_id.exists' => 'The selected member does not exist.',
        ];
    }
}
