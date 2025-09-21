<?php

namespace App\Services\User;

use App\Models\Conection;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetUserConnectionService
{
   use ResponseHelper;
    public function getUserConnection()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $connections = Conection::where('user_id', $user->id)
                                ->orWhere('connection_id', $user->id)
                                ->first();
        if (!$connections) {
            return $this->successResponse([], "No connections found.");
        }
        return $this->successResponse($connections, "User connections retrieved successfully.");
    }
}
