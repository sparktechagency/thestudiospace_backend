<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfileFollow;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ProfileFollowService
{
    use ResponseHelper;
    public function profileFollow($business_profile_id)
    {
        $user_id = Auth::id();
        $business_profile = User::find($business_profile_id);
        if (!$business_profile || $business_profile->user_type !== 'BUSINESS') {
            return $this->errorResponse("Business profile not found or not of type 'BUSINESS'.");
        }
        $existingFollow = BusinessProfileFollow::where('user_id', $user_id)
                                                ->where('business_profile_id', $business_profile_id)
                                                ->first();
        if ($existingFollow) {
            if ($existingFollow->status === 'following') {
                $existingFollow->status = 'unfollowed';
                $existingFollow->save();
                return $this->successResponse($existingFollow, "You have unfollowed the business profile.");
            } elseif ($existingFollow->status === 'unfollowed') {
                $existingFollow->status = 'following';
                $existingFollow->save();
                return $this->successResponse($existingFollow, "You are now following the business profile.");
            }
        }
        $newFollow = BusinessProfileFollow::create([
            'user_id' => $user_id,
            'business_profile_id' => $business_profile_id,
            'status' => 'following',
        ]);
        return $this->successResponse($newFollow, "You are now following the business profile.");
    }
}
