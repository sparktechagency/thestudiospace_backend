<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Models\BusinessProfileFollow;
use App\Traits\ResponseHelper;

class ProfileCountService
{
   use ResponseHelper;
  public function ProfileCount()
    {
        $profileViewCount = \DB::table('activity_log')
            ->where('subject_type', 'App\Models\BusinessProfile')
            ->where('subject_id',auth()->id())
            ->where('event', 'View business profile')
            ->count();
        $jobPostClickCount = \DB::table('activity_log')
            ->where('subject_type', 'App\Models\JobPost')
            ->where('event', 'Viewed job post')
            ->count();

         $business_following_count = BusinessProfileFollow::where('business_profile_id',auth()->id() )
                                                ->where('status','following')
                                                ->count();
        return $this->successResponse([
            'business_following-count' => $business_following_count,
            'profile_view_count' => $profileViewCount,
            'job_post_click_count' => $jobPostClickCount
        ], 'Profile counts retrieved successfully.');
    }
}
