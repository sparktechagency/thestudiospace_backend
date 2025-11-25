<?php

namespace App\Services\UserManagement;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Conection;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeleteUserService
{
    use ResponseHelper;

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse("User not found.", 404);
        }

        // Prevent deleting yourself
        if (Auth::id() == $user->id) {
            return $this->errorResponse("You cannot delete your own account.", 400);
        }

        // Optional: Prevent deleting ADMIN unless current user is ADMIN
        if ($user->role === 'ADMIN' && Auth::user()->role !== 'ADMIN') {
            return $this->errorResponse("You are not authorized to delete an admin user.", 403);
        }

        // -------------------------------------------
        // DELETE USER IMAGES (avatar + cover picture)
        // -------------------------------------------

        // 1. Delete avatar if not default
        if ($user->avatar && $user->avatar !== 'default/profile.png') {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        // 2. Delete cover picture if exists
        $userInfo = UserInfo::where('user_id', $user->id)->first();

        if ($userInfo && $userInfo->cover_picture) {
            if (Storage::disk('public')->exists($userInfo->cover_picture)) {
                Storage::disk('public')->delete($userInfo->cover_picture);
            }
        }

        // Delete UserInfo
        if ($userInfo) {
            $userInfo->delete();
        }

        // -------------------------------------------
        // DELETE CONNECTIONS
        // -------------------------------------------
        Conection::where('user_id', $user->id)
            ->orWhere('connection_id', $user->id)
            ->delete();

        // -------------------------------------------
        // DELETE USER (Soft delete recommended)
        // -------------------------------------------
        $user->delete();

        return $this->successResponse([], "User deleted successfully.");
    }
}
