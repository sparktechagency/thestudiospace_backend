<?php

namespace App\Services\User;

use App\Models\ProfileView;
use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Spatie\Activitylog\Models\Activity;

class UserProfileViewService
{
    use ResponseHelper;
    public function userProfielView($user_id)
    {
       $authUserId = auth()->id();

        // Prevent viewing own profile
        if ($authUserId == $user_id) {
            return $this->errorResponse("You cannot view your own profile.", 403);
        }

        // Fetch user info
        $userInfo = UserInfo::with(['user:id,name,email,avatar,phone_number'])
            ->where('user_id', $user_id)
            ->first();

        if (!$userInfo) {
            return $this->errorResponse("User profile not found.");
        }

        // Log profile view in profile_views table
        ProfileView::updateOrCreate(
            [
                'viewer_id'  => $authUserId,
                'visited_id' => $user_id,
            ],
            ['updated_at' => now()] // update timestamp if already exists
        );

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($userInfo)
            ->log('Viewed profile');

        return $this->successResponse($userInfo, "Profile viewed successfully.");
    }
}
