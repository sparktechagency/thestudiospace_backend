<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ReplyCommentPostService
{
    use ResponseHelper;

    public function replyComment($data, $post_id, $comment_id)
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

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('reply/images', 'public');
            $data['image'] = 'storage/' . $imagePath;
        }

        $data['user_id'] = $user->id;
        $data['comment_id'] = $comment_id;

        $reply = Reply::create($data);

        // -----------------------------
        // Notify Comment Owner
        // -----------------------------
        if ($comment->user_id != $user->id) { // Don't notify yourself
            $commentOwner = User::where('id', $comment->user_id)
                                ->whereNotNull('fcm_token')
                                ->first();

            if ($commentOwner) {
                $notificationService = new NotificationService();

                $notificationService->send($commentOwner, [
                    'title' => "New Reply",
                    'message' => "{$user->name} replied to your comment.",
                    'type' => "comment_reply",
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'reply_id' => $reply->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($reply, "Reply added successfully.");
    }
}
