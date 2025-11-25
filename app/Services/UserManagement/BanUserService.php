<?php

namespace App\Services\UserManagement;

use App\Models\User;
use App\Traits\ResponseHelper;

class BanUserService
{
    use ResponseHelper;

    public function banUser($id)
    {
        // Find user
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        // Cannot ban ADMIN
        if ($user->role === 'ADMIN') {
            return $this->errorResponse("You cannot ban an ADMIN user.");
        }

        // Toggle ban/unban
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? "User banned successfully." : "User unbanned successfully.";

        return $this->successResponse($user, $status);
    }
}
