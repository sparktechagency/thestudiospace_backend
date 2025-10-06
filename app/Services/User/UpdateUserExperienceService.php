<?php

namespace App\Services\User;

use App\Models\UserExperience;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UpdateUserExperienceService
{
    use ResponseHelper;
    public function updateUserExperience(array $data,$experience_id)
    {
        $user_experience = UserExperience::find($experience_id);
        if(!$user_experience){
            return $this->errorResponse("User Experience not found.");
        }
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $user_experience->update( $data);
        return $this->successResponse($user_experience,"Experience Updated successfully.");
    }
}
