<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ViewProfileService
{
   use ResponseHelper;
   public function viewProfile($business_profile_id)
    {
        $businessProfile = BusinessProfile::with(['user:id,name,email,avatar'])->find($business_profile_id);
        if (!$businessProfile) {
            return $this->errorResponse('Business profile not found.');
        }
        if (isset($businessProfile->social_links)) {
            $socialLinks = json_decode($businessProfile->social_links, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $businessProfile->social_links = $socialLinks;
            } else {
                return $this->errorResponse("Error decoding social links.");
            }
        }
        if ($business_profile_id == auth()->id()) {
            return $this->errorResponse("You cannot view your own business profile.");
        }
        activity()
            ->causedBy(Auth::user())
            ->performedOn($businessProfile)
            ->withProperties(['profile_id' => $business_profile_id])
            ->log('View business profile');
        return $this->successResponse($businessProfile, 'Business profile retrieved successfully.');
    }
}
