<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Traits\ResponseHelper;

class CommentLikeService
{
   use ResponseHelper;
   public function commentLike($post_id, $comment_id)
    {
        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }
        $comment = Comment::find($comment_id);
        if (!$comment) {
            return $this->errorResponse("Comment not found.");
        }
        $existingLike = CommentLike::where('comment_id', $comment_id)
                                ->where('user_id', auth()->id())
                                ->first();
        if ($existingLike) {
            $newStatus = $existingLike->status == 1 ? 0 : 1;
            $existingLike->update(['status' => $newStatus]);
            $message = $newStatus == 1 ? "Comment liked successfully." : "Comment unliked successfully.";
            return $this->successResponse($existingLike, $message);
        }
        else {
            CommentLike::create([
                'comment_id' => $comment_id,
                'user_id' => auth()->id(),
                'status' => 1,
            ]);
            return $this->successResponse([], "Comment liked successfully.");
        }
    }
}
