<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserInfoService
{
    use ResponseHelper;
    public function getUserInfo()
    {
        $user = Auth::user();
        if(!$user){
            return $this->errorResponse("User not found.");
        }
        $userInfo = UserInfo::with(['user:id,name,email,avatar,phone_number'])->where('user_id',$user->id)->first();
        return $this->successResponse($userInfo,"User info retrieved successfully.");
    }
}
