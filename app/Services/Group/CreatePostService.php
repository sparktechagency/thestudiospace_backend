<?php

namespace App\Services\Group;

use App\Models\GroupPost;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;

class CreatePostService
{
    use ResponseHelper;

    public function createPost($data, $group_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $newFiles = [];

        // Handle multiple photos
        if (isset($data['photos']) && is_array($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $path = $photo->store('group_post/photos', 'public');
                $newFiles['photos'][] = 'storage/' . $path;
            }
        }

        // Handle multiple videos
        if (isset($data['videos']) && is_array($data['videos'])) {
            foreach ($data['videos'] as $video) {
                $path = $video->store('group_post/videos', 'public');
                $newFiles['videos'][] = 'storage/' . $path;
            }
        }

        // Handle document upload
        if (isset($data['document'])) {
            $path = $data['document']->store('group_post/documents', 'public');
            $newFiles['document'] = 'storage/' . $path;
        }

        // Create group post
        $post = GroupPost::create([
            'group_id' => $group_id,
            'user_id' => $user->id,
            'content' => $data['content'] ?? null,
            'privacy' => $data['privacy'] ?? 'public',
            'photos' => !empty($newFiles['photos']) ? json_encode($newFiles['photos']) : null,
            'videos' => !empty($newFiles['videos']) ? json_encode($newFiles['videos']) : null,
            'document' => $newFiles['document'] ?? null,
        ]);

        // -----------------------------
        // Notify group members
        // -----------------------------
        $members = GroupMember::where('group_id', $group_id)
                              ->where('user_id', '!=', $user->id) // exclude post creator
                              ->pluck('user_id');

        $notificationService = new NotificationService();

        foreach ($members as $memberId) {
            $member = User::where('id', $memberId)
                          ->whereNotNull('fcm_token')
                          ->first();
            if ($member) {
                $notificationService->send($member, [
                    'title' => "New Group Post",
                    'message' => "{$user->name} posted in your group.",
                    'type' => "group_post",
                    'group_id' => $group_id,
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($post, 'Post created successfully.');
    }
}
