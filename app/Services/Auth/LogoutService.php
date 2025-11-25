<?php

namespace App\Services\Auth;

use App\Traits\ResponseHelper;

class LogoutService
{
    use ResponseHelper;
    public function logout()
    {
        $user = auth()->user();
        $user->update(['is_online'=>false]);
        auth()->logout();
        return $this->successResponse([],"Successfully logged out.");
    }

}
