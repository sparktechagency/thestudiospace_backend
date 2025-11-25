<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupComment;
use App\Models\GroupPost;
use App\Models\GroupCommentLike;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class CommentLikeService
{
    use ResponseHelper;

    public function commentLike($group_id, $group_post_id, $group_comment_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
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

        $groupComment = GroupComment::find($group_comment_id);
        if (!$groupComment) {
            return $this->errorResponse("Group comment not found.");
        }

        $existingLike = GroupCommentLike::where([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'group_comment_id' => $group_comment_id,
            'user_id' => $user->id,
        ])->first();

        $notificationService = new NotificationService();

        if ($existingLike) {
            $existingLike->status = !$existingLike->status;
            $existingLike->save();

            // Send notification if liked
            if ($existingLike->status && $groupComment->user_id != $user->id) {
                $recipient = User::find($groupComment->user_id);
                if ($recipient && $recipient->fcm_token) {
                    $notificationService->send($recipient, [
                        'title' => "New Like on Your Comment",
                        'message' => "{$user->name} liked your comment on a group post.",
                        'type' => "group_comment_like",
                        'group_id' => $group_id,
                        'post_id' => $group_post_id,
                        'comment_id' => $group_comment_id,
                        'user_id' => $user->id,
                    ]);
                }
            }

            $message = $existingLike->status ? "You liked this comment." : "You unliked this comment.";
            return $this->successResponse($existingLike, $message);
        }

        $groupCommentLike = GroupCommentLike::create([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'group_comment_id' => $group_comment_id,
            'user_id' => $user->id,
            'status' => true,
        ]);

        // Send notification for new like
        if ($groupComment->user_id != $user->id) {
            $recipient = User::find($groupComment->user_id);
            if ($recipient && $recipient->fcm_token) {
                $notificationService->send($recipient, [
                    'title' => "New Like on Your Comment",
                    'message' => "{$user->name} liked your comment on a group post.",
                    'type' => "group_comment_like",
                    'group_id' => $group_id,
                    'post_id' => $group_post_id,
                    'comment_id' => $group_comment_id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($groupCommentLike, "You liked this comment.");
    }
}
