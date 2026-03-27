<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateCoverService
{
    use ResponseHelper;

    public function updateCover($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $existingInfo = BusinessProfile::where('user_id', $user->id)->first();
        $data['user_id'] = $user->id;
        if (!empty($data['cover_picture'])) {
            if (
                $existingInfo &&
                $existingInfo->cover_picture &&
                Storage::disk('public')->exists(str_replace('storage/', '', $existingInfo->cover_picture))
            ) {
                Storage::disk('public')->delete(
                    str_replace('storage/', '', $existingInfo->cover_picture)
                );
            }
            $path = $data['cover_picture']->store('cover_pictures', 'public');
            $data['cover_picture'] = 'storage/' . $path;
        }
        $businessProfile = BusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        if ($businessProfile) {

            if ($user->user_type !== 'BUSINESS') {
                $user->user_type = 'BUSINESS';
                $user->save();
            }
            $admins = User::where('role', 'ADMIN')->get();
            $notificationData = [
                'title'   => 'Business Profile Updated',
                'message' => $user->name . ' updated their business profile.',
                'type'    => 'BUSINESS'
            ];
            (new NotificationService())->send($admins, $notificationData);
        }
        return $this->successResponse([
            'business_profile' => $businessProfile,
            'cover_picture'    => $businessProfile->cover_picture,
        ], "Business profile updated successfully.");
    }
}
