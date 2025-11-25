<?php

namespace App\Services\Home;

use App\Models\User;
use App\Traits\ResponseHelper;

class OnlineUserService
{
    use ResponseHelper;

    public function onlineUser($request)
    {
        $perPage = $request->get('per_page', 20);

        // Fetch online users where role is NOT ADMIN
        $onlineUsers = User::where('is_online', true)
            ->where('role', '!=', 'ADMIN')   // role not ADMIN
            ->select('id', 'name', 'avatar', 'email', 'phone_number', 'is_online')
            ->paginate($perPage);

        if ($onlineUsers->isEmpty()) {
            return $this->successResponse([], "No users are currently online.");
        }

        return $this->successResponse($onlineUsers, "Online users retrieved successfully.");
    }
}
