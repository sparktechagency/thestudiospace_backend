<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\PostRequest;
use App\Http\Requests\Post\RelpyCommentRequest;
use App\Services\Post\CommentLikeService;
use App\Services\Post\CommentPostService;
use App\Services\Post\GetPostService;
use App\Services\Post\GetSavedService;
use App\Services\Post\LikePostService;
use App\Services\Post\PostService;
use App\Services\Post\ReplyCommentLikeService;
use App\Services\Post\ReplyCommentPostService;
use App\Services\Post\SavedUnsavedService;
use App\Services\Post\SharePostService;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected $postService;
    protected $getPostService;
    protected $likePostService;
    protected $commentPostService;
    protected $replyCommentPostService;
    protected $sharePostService;
    protected $commentLikeService;
    protected $replyCommentLikeService;
    protected $getSavedService;
    protected $savedUnsavedService;
    public function __construct(
        PostService $postService,
        GetPostService $getPostService,
        LikePostService $likePostService,
        CommentPostService $commentPostService,
        ReplyCommentPostService $replyCommentPostService,
        SharePostService $sharePostService,
        CommentLikeService $commentLikeService,
        ReplyCommentLikeService $replyCommentLikeService,
        GetSavedService $getSavedService,
        SavedUnsavedService $savedUnsavedService,
    )
    {
        $this->postService = $postService;
        $this->getPostService = $getPostService;
        $this->likePostService = $likePostService;
        $this->commentPostService = $commentPostService;
        $this->replyCommentPostService = $replyCommentPostService;
        $this->sharePostService = $sharePostService;
        $this->commentLikeService = $commentLikeService;
        $this->savedUnsavedService =$savedUnsavedService;
        $this->replyCommentLikeService = $replyCommentLikeService;
        $this->getSavedService = $getSavedService;
        $this->savedUnsavedService = $savedUnsavedService;
    }
    public function getPost(Request $request)
    {
        return $this->execute(function() use ($request){
            return $this->getPostService->getPost($request);
        });
    }
    public function createPost(PostRequest $postRequest)
    {
        return $this->execute(function() use ($postRequest){
            $data = $postRequest->validated();
            return $this->postService->createPost($data);
        });
    }
    public function Like($post_id)
    {
        return $this->execute(function() use ($post_id){
            return $this->likePostService->like($post_id);
        });
    }
    public function comment(CommentRequest $commentRequest, $post_id)
    {
        return $this->execute(function() use ($commentRequest,$post_id){
            $data = $commentRequest->validated();
            return $this->commentPostService->comment($data,$post_id);
        });
    }
    public function replyComment(RelpyCommentRequest $relpyCommentRequest,$post_id,$comment_id)
    {
        return $this->execute(function()use($relpyCommentRequest,$post_id,$comment_id){
            $data = $relpyCommentRequest->validated();
            return $this->replyCommentPostService->replyComment($data,$post_id,$comment_id);
        });
    }
    public function share($post_id)
    {
        return $this->execute(function() use ($post_id){
            return $this->sharePostService->share($post_id);
        });
    }
    public function commentLike($post_id,$comment_id)
    {
        return $this->execute(function() use ($post_id,$comment_id){
            return $this->commentLikeService->commentLike($post_id,$comment_id);
        });
    }
    public function replyCommentLike($post_id,$comment_id,$reply_id)
    {
        return $this->execute(function()use($post_id,$comment_id,$reply_id){
            return $this->replyCommentLikeService->replyCommentLike($post_id,$comment_id,$reply_id);
        });
    }
    public function getSaved()
    {
         return $this->execute(function(){
            return $this->getSavedService->getSaved();
        });
    }
    public function savedUnsaved($post_id)
    {
        return $this->execute(function() use ($post_id){
            return $this->savedUnsavedService->savedUnsaved($post_id);
        });
    }
}
