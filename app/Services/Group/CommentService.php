<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupComment;
use App\Models\GroupPost;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseHelper;

class CommentService
{
    use ResponseHelper;

    public function comment($data, $group_id, $group_post_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.", [], 404);
        }

        $group = Group::find($group_id);
        if (!$group) {
            return $this->errorResponse("Group not found.");
        }

        $post = GroupPost::where('group_id', $group_id)
                         ->where('id', $group_post_id)
                         ->first();
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $path = $data['image']->store('group_comment/images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $commentData = [
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'user_id' => $user->id,
            'content' => $data['content'] ?? null,
            'image' => $data['image'] ?? null,
            'emoji' => $data['emoji'] ?? null,
        ];

        $comment = GroupComment::create($commentData);

        // -----------------------------
        // Send notification to post owner
        // -----------------------------
        if ($post->user_id != $user->id) { // Don't notify if commenting on own post
            $recipient = User::find($post->user_id);
            if ($recipient && $recipient->fcm_token) {
                $notificationService = new NotificationService();
                $notificationService->send($recipient, [
                    'title' => "New Comment on Your Post",
                    'message' => "{$user->name} commented on your group post.",
                    'type' => "group_post_comment",
                    'group_id' => $group_id,
                    'post_id' => $group_post_id,
                    'comment_id' => $comment->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($comment, "Comment added successfully.");
    }
}
