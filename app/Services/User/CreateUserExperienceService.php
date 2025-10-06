<?php

namespace App\Services\User;

use App\Models\UserExperience;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class CreateUserExperienceService
{
    use ResponseHelper;
   public function createUserExperience(array $data)
    {
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $user_experience = UserExperience::create( $data);
        return $this->successResponse($user_experience,"Experience create successfully.");
    }
}
