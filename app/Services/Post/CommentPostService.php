<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentPostService
{
    use ResponseHelper;

    public function comment($data, $post_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('comment/images', 'public');
            $data['image'] = 'storage/' . $imagePath;
        }

        $data['user_id'] = $user->id;
        $data['post_id'] = $post_id;

        $comment = Comment::create($data);

        // -----------------------------
        // Notify Post Owner
        // -----------------------------
        if ($post->user_id != $user->id) { // Don't notify yourself
            $postOwner = User::where('id', $post->user_id)
                            //  ->whereNotNull('fcm_token')
                             ->first();

            if ($postOwner) {
                $notificationService = new NotificationService();

                $notificationService->send($postOwner, [
                    'title' => "New Comment",
                    'message' => "{$user->name} commented on your post.",
                    'type' => "post_comment",
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($comment, "Comment added successfully.");
    }
}
