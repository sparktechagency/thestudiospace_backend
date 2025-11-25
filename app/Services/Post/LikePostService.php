<?php

namespace App\Services\Post;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class LikePostService
{
    use ResponseHelper;

    public function like($post_id, $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $post = Post::find($post_id);
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }

        // Allowed reaction types
        $allowedTypes = ['Heart','Heart-Eyes','Face-with-Tears-of-Joy','Loudly-Crying-Face','Astonished-Face','Pouting-Face'];
        if (!in_array($request->type, $allowedTypes)) {
            return $this->errorResponse("Invalid like type.");
        }

        // Mapping enum to emoji/text for notification
        $reactionText = [
            'Heart' => '❤️ liked',
            'Heart-Eyes' => '😍 reacted to',
            'Face-with-Tears-of-Joy' => '😂 reacted to',
            'Loudly-Crying-Face' => '😭 reacted to',
            'Astonished-Face' => '😲 reacted to',
            'Pouting-Face' => '😡 reacted to',
        ];

        // Check existing like
        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            // UNLIKE
            if ($like->status == true) {
                $like->status = false;
                $like->save();
                return $this->successResponse($like, "Post unliked successfully.");
            }

            // RE-LIKE
            $like->status = true;
            $like->type = $request->type;
            $like->save();

            $this->sendLikeNotification($post, $user, $reactionText[$request->type]);

            return $this->successResponse($like, "Post liked successfully.");
        }

        // CREATE NEW LIKE
        $newLike = Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type'    => $request->type,
            'status'  => true,
        ]);

        $this->sendLikeNotification($post, $user, $reactionText[$request->type]);

        return $this->successResponse($newLike, "Post liked successfully.");
    }

    // ----------------------------
    // Notification Function
    // ----------------------------
    private function sendLikeNotification($post, $likedByUser, $reactionMessage)
    {
        // Don't notify yourself
        if ($post->user_id == $likedByUser->id) {
            return;
        }

        $postOwner = User::where('id', $post->user_id)
                        //  ->whereNotNull('fcm_token')
                         ->first();

        if (!$postOwner) return;

        $notificationService = new NotificationService();

        $notificationData = [
            'title'   => "New Like",
            'message' => "{$likedByUser->name} {$reactionMessage} your post.",
            'type'    => "post_like",
            'post_id' => $post->id,
            'liked_by'=> $likedByUser->id,
        ];

        $notificationService->send($postOwner, $notificationData);
    }
}
