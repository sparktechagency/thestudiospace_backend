<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\ResponseHelper;
use GPBMetadata\Google\Api\Auth;

class updateOnlineStatusService
{
   use ResponseHelper;
   public function updateOnlineStatus($userId)
   {
        $user = User::find($userId);
        if(!$user)
        {
            return $this->errorResponse("User not found.");
        }
        $user->is_online = !$user->is_online; // true -> false, false -> true
        $user->save();

        $status = $user->is_online ? 'online' : 'offline';
        return $this->successResponse($user, "User is now $status");
   }
}
