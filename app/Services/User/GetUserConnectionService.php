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
        $connections = Conection::with(['user','connection_user'])->where('connection_id', $user->id)->get();
        if (!$connections) {
            return $this->successResponse([], "No connections found.");
        }
        return $this->successResponse($connections, "User connections retrieved successfully.");
    }
}
