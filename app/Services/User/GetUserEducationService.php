<?php

namespace App\Services\User;

use App\Models\UserEducation;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserEducationService
{
   use ResponseHelper;

    public function getUserEducation()
   {
        $user = Auth::user();
        if(!$user){
            return $this->errorResponse("User not found.");
        }
        $userInfo = UserEducation::with(['user:id,name,email,avatar,phone_number'])->where('user_id',$user->id)->get();
        return $this->successResponse($userInfo,"User Education retrieved successfully.");
   }
}
