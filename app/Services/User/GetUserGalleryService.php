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
        $userGalleries = UserGallery::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate(20);
        return $this->successResponse($userGalleries, "User gallery retrieved successfully.");
    }
}
