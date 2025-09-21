<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessGallery;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetGalleryService
{
    use ResponseHelper;
    public function getGallery()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $gallery = BusinessGallery::where('user_id', $user->id)->first();
        if (!$gallery) {
            return $this->errorResponse("Business gallery not found.");
        }
         if (isset($gallery->files)) {
            $gallery->files = json_decode($gallery->files, true); 
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->errorResponse("Error decoding gallery files.");
            }
        }
        return $this->successResponse([
            'business_gallery' => $gallery,
        ], "Business gallery fetched successfully.");
    }
}
