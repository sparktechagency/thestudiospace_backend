<?php

namespace App\Services\User;

use App\Models\UserGallery;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateUserGalleryService
{
   use ResponseHelper;

   public function updateUserGallery($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $userGallery = UserGallery::where('user_id', $user->id)->first();
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
                $path = $file->store('gallery', 'public');
                $newFiles[] = 'storage/' . $path;
            }
        }
        $userGallery = UserGallery::updateOrCreate(
            ['user_id' => $user->id],
            ['files' => json_encode($newFiles)]
        );
        return $this->successResponse($userGallery, "User gallery updated/created successfully.");
    }
}
