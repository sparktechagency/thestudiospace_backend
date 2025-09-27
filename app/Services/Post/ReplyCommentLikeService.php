<?php

namespace App\Services\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use App\Models\ReplyLike;
use App\Traits\ResponseHelper;

class ReplyCommentLikeService
{
   use ResponseHelper;
   public function replyCommentLike($post_id,$comment_id,$reply_id)
   {
        $post = Post::find($post_id);
        if(!$post){
            return $this->errorResponse("Post not found.");
        }
        $comment = Comment::find($comment_id);
        if(!$comment){
            return $this->errorResponse("Comment not found.");
        }
        $reply = Reply::find($reply_id);
        if(!$reply){
            return $this->errorResponse("Reply Comment not found.");
        }
        $replyLike = ReplyLike::where('user_id',auth()->id())->where('reply_id',$reply_id)->first();
        if($replyLike){
            if($replyLike->status == true){
                $replyLike->status = false;
                $replyLike->save();
                return $this->successResponse($replyLike,"Reply Comment unlike successfully.");
            }elseif($replyLike->status == false){
                $replyLike->status = true;
                $replyLike->save();
                return $this->successResponse($replyLike,"Reply Comment like successfully.");
            }
        }
       $replyCommentLike = ReplyLike::create([
            'reply_id' => $reply_id,
            'user_id' => auth()->id(),
            'status' => true,
        ]);
        return $this->successResponse($replyCommentLike,"Reply Comment Like successfully.");
   }
}
