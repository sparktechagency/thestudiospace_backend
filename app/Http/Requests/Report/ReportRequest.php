<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'The name field is required.',
            'name.string'          => 'The name must be a valid text.',
            'name.max'             => 'The name may not be greater than 255 characters.',

            'description.required' => 'The description field is required.',
            'description.string'   => 'The description must be a valid text.',
            'description.max'      => 'The description may not be greater than 1000 characters.',
        ];
    }
}
