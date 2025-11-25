<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\SavedPost;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetPostService
{
    use ResponseHelper;

   public function getPost($request)
{
    $authUser = Auth::user();

    $posts = Post::with([
            'user',
            // 'likes',
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

    $posts->each(function ($post) use ($authUser) {

        $post->likes_count = $post->likes()->count();
        $post->comments_count = $post->comments()->count();
        $post->shares_count = $post->shares()->count();


        $post->is_liked = $authUser
            ? $post->likes()->where('user_id', $authUser->id)->exists()
            : false;

        $post->user_reaction = $authUser
            ? $post->likes()->where('user_id', $authUser->id)->value('type')
            : null;
        $post->is_saved = $authUser
            ? SavedPost::where('user_id', $authUser->id)
                ->where('post_id', $post->id)
                ->where('status', true)
                ->exists()
            : false;


        $post->photos = $post->photos ? json_decode($post->photos, true) : [];
        $post->video = $post->video ? json_decode($post->video, true) : [];


        $reactionCounts = [
            'Heart' => $post->likes()->where('type', 'Heart')->count(),
            'Heart-Eyes' => $post->likes()->where('type', 'Heart-Eyes')->count(),
            'Face-with-Tears-of-Joy' => $post->likes()->where('type', 'Face-with-Tears-of-Joy')->count(),
            'Loudly-Crying-Face' => $post->likes()->where('type', 'Loudly-Crying-Face')->count(),
            'Astonished-Face' => $post->likes()->where('type', 'Astonished-Face')->count(),
            'Pouting-Face' => $post->likes()->where('type', 'Pouting-Face')->count(),
        ];

        // ✅ Get top 3 reaction types (names only)
        $post->top_reactions = collect($reactionCounts)
            ->filter(fn($count) => $count > 0) // only types with count > 0
            ->sortDesc()                        // sort by count descending
            ->keys()                             // get only type names
            ->take(3)                            // take top 3
            ->values();                          // reset array keys

        // ✅ Comments & replies count details
        $post->comments->each(function ($comment) {
            $comment->likes_count = $comment->likes()->count();
            $comment->replies_count = $comment->replies()->count();

            $comment->replies->each(function ($reply) {
                $reply->likes_count = $reply->likes()->count();
            });
        });
    });

    return $this->successResponse($posts, "Posts retrieved successfully.");
}

}
