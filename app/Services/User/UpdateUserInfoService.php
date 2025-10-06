<?php

namespace App\Services\User;

use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateUserInfoService
{
    use ResponseHelper;
   public function updateUserInfo(array $data)
   {
      $user = Auth::user();
        if (isset($data['cover_picture']) && $data['cover_picture']) {
            $existingInfo = UserInfo::where('user_id', $user->id)->first();
            if ($existingInfo && $existingInfo->cover_picture && Storage::disk('public')->exists($existingInfo->cover_picture)) {
                Storage::disk('public')->delete($existingInfo->cover_picture);
            }
           $path = $data['cover_picture']->store('cover_pictures', 'public');
           $data['cover_picture'] = 'storage/' . $path;
        }
        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        $user->name = $data['name'] ?? $user->name;
        $user->save();
        return $this->successResponse($userInfo,"User Information Update/Create successfully.");
   }
}
