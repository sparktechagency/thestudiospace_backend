<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateProfileService
{
    use ResponseHelper;
    public function updateProfile(array $data)
    {
        $user = Auth::user();
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            if ($user->avatar && $user->avatar !== 'default/profile.png' &&
                Storage::disk('public')->exists(str_replace('storage/', '', $user->avatar))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->avatar));
            }
            $fileName = time() . '.' . $data['avatar']->getClientOriginalExtension();
            $path = $data['avatar']->storeAs('uploads/avatars', $fileName, 'public');
            $data['avatar'] = 'storage/' . $path;
        } else {
            unset($data['avatar']);
        }
        $user->update($data);
        return $this->successResponse($user,"Profile update successfuilly.");
    }
}
