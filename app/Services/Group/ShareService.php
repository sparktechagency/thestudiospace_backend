<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\GroupShare;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ShareService
{
   use ResponseHelper;
    public function share($group_id, $group_post_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $group = Group::find($group_id);
        if (!$group) {
            return $this->errorResponse("Group not found.");
        }

        $post = GroupPost::where('group_id', $group_id)
                         ->where('id', $group_post_id)
                         ->first();
        if (!$post) {
            return $this->errorResponse("Post not found.");
        }
        $share = GroupShare::create([
            'group_id' => $group_id,
            'group_post_id' => $group_post_id,
            'user_id' => $user->id,
        ]);
        return $this->successResponse($share, "Post shared successfully.");
    }
}
