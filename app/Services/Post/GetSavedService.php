<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\SavedPost;
use App\Traits\ResponseHelper;
use Illuminate\Database\Eloquent\Casts\Json;

class GetSavedService
{
    use ResponseHelper;
   public function getSaved()
    {
        $savedPosts = SavedPost::with([
                'user:id,name,email,avatar',
                'post'
            ])
            ->where('user_id', auth()->id())
            ->where('status',true)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        if ($savedPosts->isEmpty()) {
            return $this->errorResponse("No saved posts found.");
        }
        foreach ($savedPosts as $savedPost) {
            $post = $savedPost->post;
            if ($post->photos) {
                $post->photos = json_decode($post->photos, true);
            }
            if ($post->video) {
                $post->video = json_decode($post->video, true);
            }
        }
        return $this->successResponse($savedPosts, "Saved posts retrieved successfully.");
    }
}
