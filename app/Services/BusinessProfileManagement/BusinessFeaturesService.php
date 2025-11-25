<?php

namespace App\Services\BusinessProfileManagement;

use App\Models\BusinessProfile;
use App\Traits\ResponseHelper;

class BusinessFeaturesService
{
    use ResponseHelper;

    /**
     * Toggle business status
     */
    public function businessFeature($id)
    {
        $profile = BusinessProfile::with('user')->find($id);

        if (!$profile || !$profile->user) {
            return $this->errorResponse('Business profile or user not found.');
        }

        // Toggle the is_business status
        $profile->user->is_business = !$profile->user->is_business;
        $profile->user->save();

        $status = $profile->user->is_business ? 'activated' : 'deactivated';

        return $this->successResponse(
            [
                'user_id' => $profile->user->id,
                'is_business' => $profile->user->is_business
            ],
            "Business status {$status} successfully."
        );
    }
}
