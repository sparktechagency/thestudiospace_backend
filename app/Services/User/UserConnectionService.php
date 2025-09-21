<?php

namespace App\Services\User;

use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Conection;


class UserConnectionService
{
   use ResponseHelper;

    public function userConnection($connection_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        if ($user->id == $connection_id) {
            return $this->errorResponse("You cannot connect with yourself.");
        }
        $existingConnection = Conection::where('user_id', $user->id)
                                        ->where('connection_id', $connection_id)
                                        ->exists();
        if ($existingConnection) {
            return $this->successResponse([], "Connection already exists.");
        }
        Conection::create([
            'user_id' => $user->id,
            'connection_id' => $connection_id,
        ]);
        return $this->successResponse([], "Connection created successfully.");
    }
}
