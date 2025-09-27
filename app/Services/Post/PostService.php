<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
   use ResponseHelper;
    public function createPost($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $newFiles = [];
        if (isset($data['photos']) && is_array($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $path = $photo->store('post/photos', 'public');
                $newFiles['photos'][] = 'storage/' . $path;
            }
        }
        if (isset($data['video']) && is_array($data['video'])) {
            foreach ($data['video'] as $video) {
                $videoPath = $video->store('post/videos', 'public');
                $newFiles['video'][] = 'storage/' . $videoPath;
            }
        }
        if (isset($data['document'])) {
            $documentPath = $data['document']->store('post/documents', 'public');
            $newFiles['document'] = 'storage/' . $documentPath;
        }
        $post = Post::create([
            'content' => $data['content'],
            'user_id' => $user->id,
            'privacy' => $data['privacy'] ?? 'public',
            'photos' => isset($newFiles['photos']) ? json_encode($newFiles['photos']) : null,
            'video' => isset($newFiles['video']) ? json_encode($newFiles['video']) : null,
            'document' => $newFiles['document'] ?? null,
        ]);
        return $this->successResponse($post, 'Post created successfully.');
    }
}
