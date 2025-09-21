<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessProfile;
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
        $data['user_id'] = $user->id;

         if (isset($data['cover_picture']) && $data['cover_picture']) {
            $existingInfo = BusinessProfile::where('user_id', $user->id)->first();
            if ($existingInfo && $existingInfo->cover_picture && Storage::disk('public')->exists($existingInfo->cover_picture)) {
                Storage::disk('public')->delete($existingInfo->cover_picture);
            }
           $path = $data['cover_picture']->store('cover_pictures', 'public');
           $data['cover_picture'] = 'storage/' . $path;
        }
       if (isset($data['avatar']) && $data['avatar']) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = 'storage/' . $avatarPath;
            $user->avatar = $avatarPath;
            $user->save();
        }
        if (isset($data['social_links']) && is_array($data['social_links'])) {
            $data['social_links'] = json_encode($data['social_links']);
        }
        $businessProfile = BusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        if ($businessProfile) {
            $user->user_type = 'BUSINESS';
            $user->save();
        }
        return $this->successResponse([
            'business_profile' => $businessProfile,
            'avatar' => $user->avatar, 
        ], "Business profile created/updated successfully.");
    }
}
