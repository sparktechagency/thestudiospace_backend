<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetProfileService
{
   use ResponseHelper;
   public function getProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $businessProfile = BusinessProfile::with(['user:id,name,email,avatar'])->where('user_id', $user->id)->first();
        if (!$businessProfile) {
            return $this->errorResponse("Business profile not found.");
        }
        if (isset($businessProfile->social_links)) {
            $socialLinks = json_decode($businessProfile->social_links, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $businessProfile->social_links = $socialLinks;
            } else {
                return $this->errorResponse("Error decoding social links.");
            }
        }
        return $this->successResponse([
            'business_profile' => $businessProfile,
        ], "Business profile fetched successfully.");
    }
}
