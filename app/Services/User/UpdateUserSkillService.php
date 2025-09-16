<?php

namespace App\Services\User;

use App\Models\UserSkill;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UpdateUserSkillService
{
   use ResponseHelper;
    public function updateUserSkill(array $data)
    {
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $userInfo = UserSkill::Create( $data);
        return $this->successResponse($userInfo,"User Skill Update/Create successfully.");
    }
}
