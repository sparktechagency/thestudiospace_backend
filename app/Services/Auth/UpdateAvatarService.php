<?php

namespace App\Services\Auth;

use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateAvatarService
{
    use ResponseHelper;

    public function updateAvatar($data)
    {
        $user = Auth::user();
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            if (
                $user->avatar &&
                $user->avatar !== 'default/profile.png' &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = 'storage/' . $path;

        } else {
            unset($data['avatar']);
        }
        $user->update($data);
        return $this->successResponse($user, "Profile updated successfully.");
    }
}
