<?php

namespace App\Services\User;

use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Spatie\Activitylog\Models\Activity;

class UserProfileViewService
{
    use ResponseHelper;
    public function userProfielView($user_id)
    {
        $userInfo = UserInfo::with(['user:id,name,email,avatar,phone_number'])->where('user_id',$user_id)->first();
        if (!$userInfo) {
            return $this->errorResponse("User profile not found.");
        }
        activity()
            ->causedBy(auth()->user())
            ->performedOn($userInfo)
            ->log('Viewed profile');
        return $this->successResponse($userInfo,"Profile view successfully.");
    }
}
