<?php

namespace App\Services\User;

use App\Models\UserExperience;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserExperienceService
{
   use ResponseHelper;

   public function getUserExperience()
   {
        $user = Auth::user();
        if(!$user){
            return $this->errorResponse("User not found.");
        }
        $userInfo = UserExperience::with(['user:id,name,email,avatar,phone_number'])->where('user_id',$user->id)->first();
        return $this->successResponse($userInfo,"User Experience retrieved successfully.");
   }
}
