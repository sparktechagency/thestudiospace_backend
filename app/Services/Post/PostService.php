<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\Conection;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    use ResponseHelper;

    public function createPost($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $newFiles = [];

        // -----------------------------
        // Upload Photos
        // -----------------------------
        if (isset($data['photos']) && is_array($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $path = $photo->store('post/photos', 'public');
                $newFiles['photos'][] = 'storage/' . $path;
            }
        }

        // -----------------------------
        // Upload Videos
        // -----------------------------
        if (isset($data['video']) && is_array($data['video'])) {
            foreach ($data['video'] as $video) {
                $videoPath = $video->store('post/videos', 'public');
                $newFiles['video'][] = 'storage/' . $videoPath;
            }
        }

        // -----------------------------
        // Upload Document
        // -----------------------------
        if (isset($data['document'])) {
            $documentPath = $data['document']->store('post/documents', 'public');
            $newFiles['document'] = 'storage/' . $documentPath;
        }

        // -----------------------------
        // Create Post
        // -----------------------------
        $post = Post::create([
            'content'  => $data['content'],
            'user_id'  => $user->id,
            'privacy'  => $data['privacy'] ?? 'public',
            'photos'   => isset($newFiles['photos']) ? json_encode($newFiles['photos']) : null,
            'video'    => isset($newFiles['video']) ? json_encode($newFiles['video']) : null,
            'document' => $newFiles['document'] ?? null,
        ]);


        // ==========================================
        // 🔔 Notify ALL Connected Users of this post
        // ==========================================

        // Get connected users (both ways)
        $connections = Conection::where('user_id', $user->id)
                                ->orWhere('connection_id', $user->id)
                                ->get();

        $connectedUserIds = [];

        foreach ($connections as $c) {
            $connectedUserIds[] = $c->user_id == $user->id
                                ? $c->connection_id
                                : $c->user_id;
        }

        $connectedUsers = User::whereIn('id', $connectedUserIds)
                            //   ->whereNotNull('fcm_token')
                              ->get();

        // Send Notification
        if ($connectedUsers->count() > 0) {
            $notificationService = new NotificationService();

            foreach ($connectedUsers as $connectedUser) {
                $notificationService->send($connectedUser, [
                    'title' => 'New Post',
                    'message' => $user->name . " posted something new.",
                    'type' => 'post',
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
            }
        }
        return $this->successResponse($post, 'Post created successfully.');
    }
}
