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
        $newFiles = [];
        if (isset($data['file']) && is_array($data['file'])) {
            foreach ($data['file'] as $file) {
                $path = $file->store('gallery', 'public');
                $newFiles[] = 'storage/' . $path;
            }
        }
        foreach($newFiles  as $file){
            $userGallery = UserGallery::create([
                'user_id' => $user->id,
                'file'=>$file,
            ]);
        }
        return $this->successResponse([], "User gallery updated/created successfully.");
    }
}
