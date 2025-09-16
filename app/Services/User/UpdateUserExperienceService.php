<?php

namespace App\Services\User;

use App\Models\UserExperience;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UpdateUserExperienceService
{
    use ResponseHelper;
  public function updateUserExperience(array $data)
  {
    $user = Auth::user();
    $data['user_id'] =$user->id;
    $userInfo = UserExperience::updateOrCreate( $data);
    return $this->successResponse($userInfo,"User Experience Update/Create successfully.");
  }
}
