<?php

namespace App\Services\User;

use App\Models\UserEducation;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UpdateUserEducationService
{
   use ResponseHelper;
   public function updateUserEducation($data,$education_id)
   {
        $education = UserEducation::find($education_id);
        if(!$education){
            return $this->errorResponse("Education not found.");
        }
        $user = Auth::user();
        $data['user_id'] =$user->id;
        $education->update( $data);
        return $this->successResponse($education,"Education Update successfully.");
   }
}
