<?php

namespace App\Services\Auth;

use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class UserProfileService
{
    use ResponseHelper;
    public function getProfile()
    {
        $user = Auth::user();
        return $this->successResponse($user,"Profile retrived successful.");
    }
}
