<?php

namespace App\Services\User;

use App\Models\UserExperience;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SingleExperienceService
{
   use ResponseHelper;

   public function singleExperience($experience_id)
   {
        $user = Auth::user();
        if(!$user){
            return $this->errorResponse("User not found.");
        }
        $userInfo = UserExperience::with(['user:id,name,email,avatar,phone_number'])->find($experience_id);
        if(!$userInfo){
            return $this->errorResponse("User experience not found.");
        }
        return $this->successResponse($userInfo,"User Experience retrieved successfully.");
   }
}
