<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\ReplyLike;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ReplyCommentLikeService
{
    use ResponseHelper;

    public function replyCommentLike($post_id, $comment_id, $reply_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $post = Post::find($post_id);
        if (!$post) return $this->errorResponse("Post not found.");

        $comment = Comment::find($comment_id);
        if (!$comment) return $this->errorResponse("Comment not found.");

        $reply = Reply::find($reply_id);
        if (!$reply) return $this->errorResponse("Reply not found.");

        $existingLike = ReplyLike::where('user_id', $user->id)
                                 ->where('reply_id', $reply_id)
                                 ->first();

        if ($existingLike) {
            $existingLike->status = !$existingLike->status;
            $existingLike->save();

            // Send notification only on LIKE
            if ($existingLike->status) {
                $this->sendReplyLikeNotification($reply, $user);
            }

            $message = $existingLike->status ? "Reply liked successfully." : "Reply unliked successfully.";
            return $this->successResponse($existingLike, $message);
        }

        // Create new like
        $newLike = ReplyLike::create([
            'reply_id' => $reply_id,
            'user_id' => $user->id,
            'status' => true,
        ]);

        // Send notification
        $this->sendReplyLikeNotification($reply, $user);

        return $this->successResponse($newLike, "Reply liked successfully.");
    }

    // ----------------------------
    // Notification for Reply Owner
    // ----------------------------
    private function sendReplyLikeNotification($reply, $likedByUser)
    {
        // Don't notify yourself
        if ($reply->user_id == $likedByUser->id) return;

        $replyOwner = User::where('id', $reply->user_id)
                          ->whereNotNull('fcm_token')
                          ->first();

        if (!$replyOwner) return;

        $notificationService = new NotificationService();

        $notificationService->send($replyOwner, [
            'title' => "Reply Liked",
            'message' => "{$likedByUser->name} liked your reply.",
            'type' => "reply_like",
            'reply_id' => $reply->id,
            'comment_id' => $reply->comment_id,
            'post_id' => $reply->post_id,
            'user_id' => $likedByUser->id,
        ]);
    }
}
