<?php

namespace App\Services\User;

use App\Models\UserGallery;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserGalleryService
{
    use ResponseHelper;
    public function getUserGallery()
    {
        $user = Auth::user();
        $userGallery = UserGallery::with(['user'])->where('user_id', $user->id)->first();
        if (!$userGallery) {
            return $this->errorResponse("User gallery not found.");
        }
        $files = json_decode($userGallery->files, true);
        if ($files === null) {
            return $this->errorResponse("Failed to decode gallery files.");
        }
        return $this->successResponse($files, "User gallery fetched successfully.");
    }

}
