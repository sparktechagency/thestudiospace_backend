<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\GroupComment;
use App\Models\GroupReplyComment;
use App\Models\GroupReplyCommentLike;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ReplyCommentLikeService
{
    use ResponseHelper;

    public function replyCommentLike($group_id, $group_post_id, $group_comment_id, $reply_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $group = Group::find($group_id);
        if (!$group) {
            return $this->errorResponse("Group not found.", [], 404);
        }

        $post = GroupPost::where('group_id', $group_id)
                         ->where('id', $group_post_id)
                         ->first();
        if (!$post) {
            return $this->errorResponse("Group post not found.", [], 404);
        }

        $comment = GroupComment::find($group_comment_id);
        if (!$comment) {
            return $this->errorResponse("Group comment not found.", [], 404);
        }

        $reply = GroupReplyComment::find($reply_id);
        if (!$reply) {
            return $this->errorResponse("Reply not found.", [], 404);
        }

        // Check if user already liked this reply
        $existingLike = GroupReplyCommentLike::where([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'group_comment_id' => $group_comment_id,
            'group_reply_comment_id' => $reply_id, // corrected column name
            'user_id' => $user->id,
        ])->first();

        if ($existingLike) {
            $existingLike->status = !$existingLike->status;
            $existingLike->save();

            $message = $existingLike->status ? "Reply liked successfully." : "Reply unliked successfully.";
            return $this->successResponse($existingLike, $message);
        }

        // Create a new like
        $like = GroupReplyCommentLike::create([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'group_comment_id' => $group_comment_id,
            'group_reply_comment_id' => $reply_id, // corrected column name
            'user_id' => $user->id,
            'status' => true,
        ]);

        // Send notification to reply owner if not the current user
        $replyOwner = $reply->user ?? null;
        if ($replyOwner && $replyOwner->id != $user->id) {
            $notificationService = new NotificationService();
            $notificationService->send($replyOwner, [
                'title' => 'New Like',
                'message' => $user->name . " liked your reply on a group comment.",
                'type' => 'group_reply_like',
                'group_id' => $group_id,
                'group_post_id' => $group_post_id,
                'group_comment_id' => $group_comment_id,
                'group_reply_comment_id' => $reply_id,
                'liker_id' => $user->id,
            ]);
        }
        return $this->successResponse($like, "Reply liked successfully.");
    }
}
