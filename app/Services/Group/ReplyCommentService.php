<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\GroupComment;
use App\Models\GroupReplyComment;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ReplyCommentService
{
    use ResponseHelper;

    public function replyComment($data, $group_id, $group_post_id, $group_comment_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.", [], 404);
        }
        $group = Group::find($group_id);
        if (!$group) {
            return $this->errorResponse("Group not found.", [], 404);
        }
        $post = GroupPost::where('group_id', $group_id)
            ->where('id', $group_post_id)
            ->first();
        if (!$post) {
            return $this->errorResponse("Post not found.", [], 404);
        }
        $comment = GroupComment::find($group_comment_id);
        if (!$comment) {
            return $this->errorResponse("Comment not found.", [], 404);
        }
        if (isset($data['image']) && $data['image']) {
            $path = $data['image']->store('group_comment_replies/images', 'public');
            $data['image'] = 'storage/' . $path;
        }
        $replyData = [
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'group_comment_id' => $group_comment_id,
            'user_id' => $user->id,
            'content' => $data['content'] ?? null,
            'image' => $data['image'] ?? null,
            'emoji' => $data['emoji'] ?? null,
        ];
        $reply = GroupReplyComment::create($replyData);
        if ($comment->user_id != $user->id) {
            $commentOwner = $comment->user;
            $notificationService = new NotificationService();
            $notificationService->send($commentOwner, [
                'title' => 'New Reply',
                'message' => $user->name . " replied to your comment on a group post.",
                'type' => 'group_comment_reply',
                'group_id' => $group_id,
                'group_post_id' => $group_post_id,
                'group_comment_id' => $group_comment_id,
                'replier_id' => $user->id,
            ]);
        }
        return $this->successResponse($reply, "Reply added successfully.");
    }
}
