<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Traits\ResponseHelper;


class GetPostService
{
    use ResponseHelper;
    public function getPost($request)
    {
        $posts = Post::with([
                'user',
                'likes',
                'comments.user',
                'comments.likes',
                'comments.replies',
                'comments.replies.likes',
                'shares',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(25);
        if ($posts->isEmpty()) {
            return $this->successResponse("No posts found");
        }
        $posts->each(function ($post) {
            $post->likes_count = $post->likes()->count();
            $post->comments_count = $post->comments()->count();
            $post->shares_count = $post->shares()->count();
            $post->comments->each(function ($comment) {
                $comment->likes_count = $comment->likes()->count();
                $comment->replies_count = $comment->replies()->count();
                $comment->replies->each(function ($reply) {
                    $reply->likes_count = $reply->likes()->count();
                });
            });
        });
        foreach ($posts as $post) {
            if ($post->photos) {
                $post->photos = json_decode($post->photos, true);
            }
            if ($post->video) {
                $post->video = json_decode($post->video, true);
            }
        }
        return $this->successResponse($posts, "Posts retrieved successfully.");
    }
}
