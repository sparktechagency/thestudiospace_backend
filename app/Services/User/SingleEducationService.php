<?php

namespace App\Services\User;

use App\Models\UserEducation;
use App\Traits\ResponseHelper;

class SingleEducationService
{
    use ResponseHelper;
    public function singleEducation($education_id)
    {
        $userEducation = UserEducation::with(['user:id,name,email,avatar,phone_number'])->find($education_id);
        if (!$userEducation) {
            return $this->errorResponse("User education not found.", [], 404);
        }
        return $this->successResponse($userEducation, "User education retrieved successfully.");
    }

}
