<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\CreateRequest;
use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\PostRequest;
use App\Services\Group\CommentLikeService;
use App\Services\Group\CommentService;
use App\Services\Group\CreatePostService;
use App\Services\Group\CreateService;
use App\Services\Group\GetSavedPostService;
use App\Services\Group\InviteMemberService;
use App\Services\Group\JoinService;
use App\Services\Group\LikeService;
use App\Services\Group\ReplyCommentLikeService;
use App\Services\Group\ReplyCommentService;
use App\Services\Group\SavedUnsavedService;
use App\Services\Group\ShareService;
use App\Services\Group\ViewService;
use Illuminate\Http\Request;

class GroupPostController extends Controller
{
    protected $createService;
    protected $viewService;
    protected $inviteMemberService;
    protected $joinService;
    protected $createPostServcie;
    protected $likeService;
    protected $commmentService;
    protected $shareService;
    protected $commentLikeService;
    protected $replyCommentService;
    protected $replyCommentLikeService;
    protected $savedUnsavedService;
    protected $getSavedPostService;

    public function __construct(
        CreateService $createService,
        ViewService $viewService,
        InviteMemberService $inviteMemberService,
        JoinService $joinService,
        CreatePostService $createPostService,
        LikeService $likeService,
        CommentService $commentService,
        ShareService $shareService,
        CommentLikeService $commentLikeService,
        ReplyCommentService $replyCommentService,
        ReplyCommentLikeService $replyCommentLikeService,
        SavedUnsavedService $savedUnsavedService,
        GetSavedPostService $getSavedPostService,
    ){
        $this->createService = $createService;
        $this->viewService = $viewService;
        $this->inviteMemberService = $inviteMemberService;
        $this->joinService = $joinService;
        $this->createPostServcie = $createPostService;
        $this->likeService = $likeService;
        $this->commmentService = $commentService;
        $this->shareService = $shareService;
        $this->commentLikeService = $commentLikeService;
        $this->replyCommentService = $replyCommentService;
        $this->replyCommentLikeService = $replyCommentLikeService;
        $this->savedUnsavedService = $savedUnsavedService;
        $this->getSavedPostService = $getSavedPostService;
    }
    public function createGroup(CreateRequest $createRequest)
    {
        return $this->execute(function()use($createRequest){
            $data = $createRequest->validated();
            return $this->createService->createGroup($data);
        });
    }
    public function viewGroup($group_id)
    {
        return $this->execute(function()use($group_id){
            return $this->viewService->viewGroup($group_id);
        });
    }
    public function inviteMember(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->inviteMemberService->inviteMember($request);
        });
    }
    public function joinGroup(Request $request,$group_id)
    {
        return $this->execute(function()use($request,$group_id){
            return $this->joinService->joinGroup($request,$group_id);
        });
    }
    public function createPost(PostRequest $postRequest,$group_id)
    {
        return $this->execute(function()use($postRequest,$group_id){
            $data = $postRequest->validated();
            return $this->createPostServcie->createPost($data,$group_id);
        });
    }
    public function like($group_id,$group_post_id,Request $request)
    {
        return $this->execute(function()use($group_id,$group_post_id,$request){
            return $this->likeService->like($group_id,$group_post_id,$request);
        });
    }
    public function comment(CommentRequest $commentRequest,$group_id,$group_post_id)
    {
        return $this->execute(function()use($commentRequest,$group_id,$group_post_id){
            $data = $commentRequest->validated();
            return $this->commmentService->comment($data,$group_id,$group_post_id);
        });
    }
   public function share($group_id,$group_post_id)
    {
        return $this->execute(function()use($group_id,$group_post_id){
            return $this->shareService->share($group_id,$group_post_id);
        });
    }
    public function commentLike($group_id,$group_post_id,$group_comment_id)
    {
        return $this->execute(function()use($group_id,$group_post_id,$group_comment_id){
            return $this->commentLikeService->commentLike($group_id,$group_post_id,$group_comment_id);
        });
    }
    public function replyComment(CommentRequest $commentRequest,$group_id,$group_post_id,$group_comment_id)
    {
        return $this->execute(function()use($commentRequest,$group_id,$group_post_id,$group_comment_id){
            $data = $commentRequest->validated();
            return $this->replyCommentService->replyComment($data,$group_id,$group_post_id,$group_comment_id);
        });
    }
    public function replyCommentLike($group_id,$group_post_id,$group_comment_id,$reply_id)
    {
        return $this->execute(function()use($group_id,$group_post_id,$group_comment_id, $reply_id){
            return $this->replyCommentLikeService->replyCommentLike($group_id,$group_post_id,$group_comment_id,$reply_id);
        });
    }
    public function savedUnsaved($group_id, $group_post_id)
    {
        return $this->execute(function()use($group_id,$group_post_id){
            return $this->savedUnsavedService->savedUnsaved($group_id,$group_post_id);
        });
    }

}
