<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class CommentLikeService
{
    use ResponseHelper;

    public function commentLike($post_id, $comment_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }

        $comment = Comment::find($comment_id);
        if (!$comment) {
            return $this->errorResponse("Comment not found.");
        }

        $existingLike = CommentLike::where('comment_id', $comment_id)
                                   ->where('user_id', $user->id)
                                   ->first();

        if ($existingLike) {
            $newStatus = $existingLike->status == 1 ? 0 : 1;
            $existingLike->update(['status' => $newStatus]);

            // Send notification only on LIKE
            if ($newStatus == 1) {
                $this->sendCommentLikeNotification($comment, $user);
            }

            $message = $newStatus == 1 ? "Comment liked successfully." : "Comment unliked successfully.";
            return $this->successResponse($existingLike, $message);
        } else {
            $newLike = CommentLike::create([
                'comment_id' => $comment_id,
                'user_id' => $user->id,
                'status' => 1,
            ]);

            $this->sendCommentLikeNotification($comment, $user);

            return $this->successResponse($newLike, "Comment liked successfully.");
        }
    }

    // ----------------------------
    // Notification for Comment Owner
    // ----------------------------
    private function sendCommentLikeNotification($comment, $likedByUser)
    {
        // Don't notify yourself
        if ($comment->user_id == $likedByUser->id) return;

        $commentOwner = User::where('id', $comment->user_id)
                            // ->whereNotNull('fcm_token')
                            ->first();

        if (!$commentOwner) return;

        $notificationService = new NotificationService();

        $notificationService->send($commentOwner, [
            'title' => "Comment Liked",
            'message' => "{$likedByUser->name} liked your comment.",
            'type' => "comment_like",
            'comment_id' => $comment->id,
            'post_id' => $comment->post_id,
            'user_id' => $likedByUser->id,
        ]);
    }
}
