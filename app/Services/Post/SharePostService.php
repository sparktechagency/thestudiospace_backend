<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\Share;
use App\Traits\ResponseHelper;

class SharePostService
{
   use ResponseHelper;

   public function share($post_id)
    {
        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }
        $user_id = auth()->id();
        if (!$user_id) {
            return $this->errorResponse("User is not authenticated.");
        }
        $data = [
            'user_id' => $user_id,
            'post_id' => $post_id,
        ];
        $share = Share::create($data);
        return $this->successResponse($share, "Post shared successfully.");
    }
}
