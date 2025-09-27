<?php

namespace App\Services\Post;

use App\Models\Like;
use App\Models\Post;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class LikePostService
{
    use ResponseHelper;
   public function like($post_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }
        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->first();
        if ($like) {
            if ($like->status == false) {
                $like->status = true;
                $like->save();
                return $this->successResponse($like, "Post liked successfully.");
            } elseif ($like->status == true) {
                $like->status = false;
                $like->save();
                return $this->successResponse($like, "Post unliked successfully.");
            }
        }
        $newLike = Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'status' => true,
        ]);
        return $this->successResponse($newLike, "Post liked successfully.");
    }
}
