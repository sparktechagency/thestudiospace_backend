<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\SavedPost;
use App\Traits\ResponseHelper;

class SavedUnsavedService
{
   use ResponseHelper;
   public function savedUnsaved($post_id)
    {
        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }
        $savedPostExist = SavedPost::where('post_id', $post_id)
                                ->where('user_id', auth()->id())
                                ->first();
        if ($savedPostExist) {
            if ($savedPostExist->status == true) {
                $savedPostExist->status = false;
                $savedPostExist->save();
                return $this->successResponse($savedPostExist, "Unsaved post successfully.");
            } elseif ($savedPostExist->status == false) {
                $savedPostExist->status = true;
                $savedPostExist->save();
                return $this->successResponse($savedPostExist, "Saved post successfully.");
            }
        }
        $savedPost = SavedPost::create([
            'user_id' => auth()->id(),
            'post_id' => $post_id,
            'status' => true,
        ]);
        return $this->successResponse($savedPost, "Saved post successfully.");
    }
}
