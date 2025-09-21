<?php

namespace App\Services\User;

use App\Models\UserSkill;
use App\Traits\ResponseHelper;

class DeleteUserSkillService
{
    use ResponseHelper;
    public function deleteUserSkill($skill_id)
    {
       $userSkill = UserSkill::where('user_id',auth()->user()->id)->where('id',$skill_id)->first();
       if(!$userSkill){
        return $this->errorResponse("User Skill not found.");
       }
       $userSkill->delete();
       return $this->successResponse([],"User Skill delete successfully.");
    }
}
