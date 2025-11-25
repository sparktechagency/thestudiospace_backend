<?php

namespace App\Services\User;

use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoverImageUpdateService
{
    use ResponseHelper;

    public function updateUserInfoCover($data)
    {
        $user = Auth::user();

        if (!$user) {
            return $this->errorResponse("User not authenticated.", 401);
        }

        // Get user's info record
        $userInfo = UserInfo::firstOrCreate(['user_id' => $user->id]);

        if (isset($data['cover_picture']) && $data['cover_picture']) {
            // Delete old cover image if exists
            if ($userInfo->cover_picture && Storage::disk('public')->exists($userInfo->cover_picture)) {
                Storage::disk('public')->delete($userInfo->cover_picture);
            }

            // Store new cover image
            $path = $data['cover_picture']->store('cover_pictures', 'public');

            // Save path to userInfo
            $userInfo->cover_picture = $path;
            $userInfo->save();
        }

        return $this->successResponse([
            'cover_picture_url' => asset('storage/' . $userInfo->cover_picture)
        ], "Cover image updated successfully.");
    }
}
