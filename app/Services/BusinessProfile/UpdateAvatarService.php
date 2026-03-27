<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateAvatarService
{
    use ResponseHelper;

    public function updateAvatar($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $existingInfo = BusinessProfile::where('user_id', $user->id)->first();
        $data['user_id'] = $user->id;
        if (!empty($data['avatar'])) {
            if (
                $existingInfo &&
                $existingInfo->avatar &&
                Storage::disk('public')->exists(str_replace('storage/', '', $existingInfo->avatar))
            ) {
                Storage::disk('public')->delete(
                    str_replace('storage/', '', $existingInfo->avatar)
                );
            }
            $path = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = 'storage/' . $path;
        }
        $businessProfile = BusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        if (!empty($data['avatar'])) {
            $user->avatar = $data['avatar'];
        }
        if ($user->user_type !== 'BUSINESS') {
            $user->user_type = 'BUSINESS';
        }
        $user->save();
        return $this->successResponse([
            'avatar' => $businessProfile,
        ], "Avatar updated successfully.");
    }
}
