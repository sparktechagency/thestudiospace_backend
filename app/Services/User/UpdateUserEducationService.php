<?php

namespace App\Services\User;

use App\Models\UserEducation;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UpdateUserEducationService
{
   use ResponseHelper;
   public function updateUserEducation($data)
   {
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $userInfo = UserEducation::updateOrCreate( $data);
        return $this->successResponse($userInfo,"User Education Update/Create successfully.");
   }
}
