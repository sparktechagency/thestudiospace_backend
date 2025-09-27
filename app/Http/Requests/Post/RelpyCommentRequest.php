<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class RelpyCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'emoji' => 'nullable|string|max:10',
        ];
    }
}
