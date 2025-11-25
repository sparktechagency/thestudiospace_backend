<?php

namespace App\Services\BusinessProfileManagement;

use App\Models\BusinessProfile;
use App\Traits\ResponseHelper;

class PostPermissionService
{
    use ResponseHelper;

    public function postPermission($id)
    {
       $profile = BusinessProfile::with('user')->find($id);

        if (!$profile || !$profile->user) {
            return $this->errorResponse('Business profile or user not found.');
        }

        // Toggle the post permission
        $profile->user->is_post = !$profile->user->is_post;
        $profile->user->save();

        $status = $profile->user->is_post ? 'granted' : 'revoked';

        return $this->successResponse(
            $profile,
            "Post permission {$status} successfully."
        );
    }
}
