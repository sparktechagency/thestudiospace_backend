<?php

namespace App\Services\User;

use App\Models\UserEducation;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class CreateUserEducationService
{
    use ResponseHelper;
   public function createUserEducation(array $data)
    {
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $education = UserEducation::create($data);
        return $this->successResponse($education,"Education create successfully.");
    }
}
