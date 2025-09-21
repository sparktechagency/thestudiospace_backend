<?php

namespace App\Services\BusinessProfile;

use App\Models\BusinessGallery;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
   use ResponseHelper;
   public function Gallery($data)
   {
         $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $userGallery = BusinessGallery::where('user_id', $user->id)->first();
        if ($userGallery && $userGallery->files) {
            $files = json_decode($userGallery->files, true);
            if (is_array($files)) {
                foreach ($files as $oldFile) {
                    if (Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
        }
        $newFiles = [];
        if (isset($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $file) {
                $path = $file->store('businessGallery', 'public');
                $newFiles[] = 'storage/' . $path;
            }
        }
        $userGallery = BusinessGallery::updateOrCreate(
            ['user_id' => $user->id],
            ['files' => json_encode($newFiles)]
        );
        return $this->successResponse($userGallery, "Business gallery updated/created successfully.");
    }
}
