<?php

namespace App\Services\UserManagement;

use App\Models\User;
use App\Models\Conection;
use App\Models\Post; // <-- Only if your posts table uses this model
use App\Traits\ResponseHelper;

class ViewUserService
{
    use ResponseHelper;

    public function viewUser($id)
    {
        $user = User::with('userInfo')->find($id);
        if (!$user) {
            return $this->errorResponse("User not found.", 404);
        }
        $info = $user->userInfo;
        $totalPosts = Post::where('user_id', $user->id)->count();
        $totalConnections = Conection::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('connection_id', $user->id);
            })
            ->where('status', 'accepted')
            ->count();

        // Prepare response data
        $data = [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'avatar'            => $user->avatar,
            'phone_number'      => $user->phone_number,
            'role'              => $user->role,
            'is_banned'         => $user->is_banned,
            'total_posts'       => $totalPosts,

            // UserInfo section
            'location'          => $info->location ?? null,
            'bio'               => $info->bio ?? null,
            'job_title'         => $info->job_title ?? null,
            'company'           => $info->company_name ?? null,

            // Total connections
            'total_connections' => $totalConnections,
        ];

        return $this->successResponse($data, "User profile retrieved successfully.");
    }
}
