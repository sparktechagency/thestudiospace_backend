<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'content' => 'nullable|string|max:2000',  // Limit content to a maximum of 2000 characters
            'photos' => 'nullable|array',  // Ensure photos is an array
            'photos.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,bmp|max:102400', // Each photo must be a valid file (up to 100MB)
            'video' => 'nullable|array', // The video field can be an array
            'video.*' => 'nullable|file|mimes:mp4,avi,mov|max:102400', // Each video file must be valid (up to 100MB)
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg',  // Document file types
            'privacy' => 'in:public,connections,private',  // Valid privacy values
        ];
    }

    public function messages()
    {
        return [
            'content.max' => 'The content may not be greater than 2000 characters.',
            'photos.*.file' => 'Each photo must be a valid file.',  // Corrected to validate file type
            'photos.*.mimes' => 'Each photo must be of type: jpg, jpeg, png, gif, bmp.', // Custom message for valid photo file types
            'photos.*.max' => 'Each photo size may not be greater than 100 MB.', // Custom message for max file size
            'video.*.file' => 'Each video must be a valid file.', // Custom message for each video file
            'video.*.mimes' => 'Each video must be of type: mp4, avi, mov.', // Custom message for valid video file types
            'video.*.max' => 'Each video size may not be greater than 100 MB.', // Custom message for max video file size
            'document.mimes' => 'The document must be a file of type: pdf, doc, docx, jpg, png, jpeg.', // Custom message for document types
            'privacy.in' => 'The privacy must be one of the following values: public, connections, private.',  // Privacy validation message
        ];
    }

}

