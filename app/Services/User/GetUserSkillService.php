<?php

namespace App\Services\User;

use App\Models\UserSkill;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserSkillService
{
    use ResponseHelper;
    public function getUserSkill()
    {
        $user = Auth::user();
        if(!$user){
            return $this->errorResponse("User not found.");
        }
        $userInfo = UserSkill::where('user_id',$user->id)->get();
        return $this->successResponse($userInfo,"User Skill retrieved successfully.");
    }
}
