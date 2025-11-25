<?php

namespace App\Services\User;

use App\Models\UserGallery;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class DeleteGalleryService
{
  use ResponseHelper;
 public function deleteGallery($gallery_id)
    {
        $userGallery = UserGallery::where('id', $gallery_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$userGallery) {
            return $this->errorResponse("User gallery not found.");
        }
        if ($userGallery->file && Storage::disk('public')->exists(str_replace('storage/', '', $userGallery->file))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $userGallery->file));
        }
        $userGallery->delete();
        return $this->successResponse([], "Gallery deleted successfully.");
    }

}
