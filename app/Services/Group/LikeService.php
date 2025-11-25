<?php

namespace App\Services\Group;

use App\Models\GroupLike;
use App\Models\GroupPost;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseHelper;

class LikeService
{
    use ResponseHelper;

    public function like(int $group_id, int $group_post_id, $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found", [], 404);
        }

        $post = GroupPost::where('group_id', $group_id)
                         ->where('id', $group_post_id)
                         ->first();

        if (!$post) {
            return $this->errorResponse("Post not found", [], 404);
        }

        $allowedTypes = ['like', 'love', 'angry'];
        if (!in_array($request->type, $allowedTypes)) {
            return $this->errorResponse("Invalid like type.");
        }

        $like = GroupLike::where('user_id', $user->id)
                         ->where('group_post_id', $post->id)
                         ->where('type', $request->type)
                         ->first();

        if ($like) {
            $like->status = !$like->status;
            $like->save();
            $message = $like->status ? "Post liked successfully." : "Post unliked successfully.";
            return $this->successResponse($like, $message);
        }

        $newLike = GroupLike::create([
            'group_id' => $group_id,
            'group_post_id' => $post->id,
            'user_id' => $user->id,
            'type' => $request->type,
            'status' => true,
        ]);
        if ($post->user_id != $user->id) {
            $postOwner = User::find($post->user_id);
            if ($postOwner && $postOwner->fcm_token) {
                $notificationService = new NotificationService();
                $notificationService->send($postOwner, [
                    'title' => 'New Like',
                    'message' => $user->name . " reacted '{$request->type}' on your group post.",
                    'type' => 'group_post_like',
                    'group_post_id' => $post->id,
                    'liker_id' => $user->id,
                ]);
            }
        }
        return $this->successResponse($newLike, "Post liked successfully.");
    }
}
