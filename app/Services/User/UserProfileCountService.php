<?php

namespace App\Services\User;

use App\Models\Conection;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class UserProfileCountService
{
    use ResponseHelper;
    public function userProfileCount()
    {
        $user = Auth::user();
        $connectionCount = Conection::where('user_id', $user->id)->count();
        $profileViewCount = \Spatie\Activitylog\Models\Activity::where('description', 'Viewed profile')
            ->where('subject_id', $user->id)
            ->where('subject_type', 'App\Models\User')
            ->count();
        return $this->successResponse([
            'connection_count' => $connectionCount,
            'profile_view_count' => $profileViewCount
        ], 'User profile counts fetched successfully.');
    }
}
