<?php

namespace App\Services\Network;

use App\Models\Conection;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class DiscoverService
{
    use ResponseHelper;

    public function discovers($request)
    {
        $keyword = $request->keyword ?? null;
        $authUser = Auth::user();

        // Get IDs of users already connected or have pending/accepted connection
        $connectedUserIds = Conection::where(function($q) use ($authUser) {
                $q->where('user_id', $authUser->id)
                  ->orWhere('connection_id', $authUser->id);
            })
            ->pluck('user_id', 'connection_id') // get both sides
            ->flatten()
            ->unique()
            ->toArray();

        // Exclude authenticated user and already connected users, only users with userInfo
        $users = User::with('userInfo')
            ->where('id', '!=', $authUser->id)
            ->whereNotIn('id', $connectedUserIds)
            ->whereHas('userInfo') // ✅ only users with userInfo
            ->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhereHas('userInfo', function($q2) use ($keyword) {
                      $q2->where('job_title', 'like', "%$keyword%")
                         ->orWhere('company_name', 'like', "%$keyword%");
                  });
            })
            ->limit(20)
            ->get();

        // Add extra info
        $users->each(function ($user) {
            $info = $user->userInfo;

            $user->designation = $info->job_title
                ? $info->job_title . " at " . ($info->company_name ?? '')
                : null;

            $user->mutual_connections = 0; // no connections yet
            $user->badge = null;            // optional
            $user->is_connected = false;
        });

        if ($users->isEmpty()) {
            return $this->successResponse([], 'No users found.');
        }

        return $this->successResponse($users, 'Users retrieved successfully.');
    }
}
