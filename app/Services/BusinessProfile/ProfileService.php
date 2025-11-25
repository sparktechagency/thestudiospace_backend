<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    use ResponseHelper;

    public function profile($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        $existingInfo = BusinessProfile::where('user_id', $user->id)->first();
        $data['user_id'] = $user->id;

        // Handle cover picture
        if (!empty($data['cover_picture'])) {
            if ($existingInfo && $existingInfo->cover_picture && Storage::disk('public')->exists(str_replace('storage/', '', $existingInfo->cover_picture))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $existingInfo->cover_picture));
            }
            $path = $data['cover_picture']->store('cover_pictures', 'public');
            $data['cover_picture'] = 'storage/' . $path;
        }

        // Handle avatar
        if (!empty($data['avatar'])) {
            if ($existingInfo && $existingInfo->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $existingInfo->avatar))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $existingInfo->avatar));
            }
            $avatarPath = $data['avatar']->store('business_avatars', 'public');
            $data['avatar'] = 'storage/' . $avatarPath;
        }

        // Handle social links
        if (!empty($data['social_links']) && is_array($data['social_links'])) {
            $data['social_links'] = json_encode($data['social_links']);
        }

        // Update or create business profile
        $businessProfile = BusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        // Update user type if profile saved
        if ($businessProfile) {
            $user->user_type = 'BUSINESS';
            $user->save();

             $admins = User::where('role', 'ADMIN')->get();
             $notificationData = [
                'title' => 'User Updated Business Profile',
                'message' => $user->name . ' has updated/created their business profile.',
                'type' => $user->user_type ?? 'USER'

            ];
            $notificationService = new NotificationService();
            $notificationService->send($admins, $notificationData);
        }

        return $this->successResponse([
            'business_profile' => $businessProfile,
            'avatar' => $businessProfile->avatar ?? $user->avatar,
        ], "Business profile created/updated successfully.");
    }
}
