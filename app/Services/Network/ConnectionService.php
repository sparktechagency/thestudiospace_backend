<?php

namespace App\Services\Network;

use App\Models\Conection;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ConnectionService
{
    use ResponseHelper;
    public function connections($request)
    {
        $authUser = Auth::user();
        $keyword = $request->get('keyword', '');
        $userIds = Conection::where(function ($q) use ($authUser) {
                $q->where('user_id', $authUser->id)
                  ->orWhere('connection_id', $authUser->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($connection) use ($authUser) {
                return $connection->user_id === $authUser->id
                    ? $connection->connection_id
                    : $connection->user_id;
            })
            ->unique()
            ->toArray();
        $users = User::with('userInfo')
            ->whereIn('id', $userIds)
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->where('name', 'like', "%$keyword%")
                      ->orWhereHas('userInfo', function ($q2) use ($keyword) {
                          $q2->where('job_title', 'like', "%$keyword%")
                             ->orWhere('company_name', 'like', "%$keyword%");
                      });
                }
            })
            ->get();
        $users->each(function ($user) use ($authUser) {
            $info = $user->userInfo;
            $user->designation = $info && $info->job_title
                ? $info->job_title . ' at ' . ($info->company_name ?? '')
                : null;
            $user->mutual_connections = Conection::where(function($q) use ($authUser, $user) {
                $q->where('user_id', $authUser->id)
                  ->where('connection_id', $user->id)
                  ->where('status', 'accepted');
            })->orWhere(function($q) use ($authUser, $user) {
                $q->where('user_id', $user->id)
                  ->where('connection_id', $authUser->id)
                  ->where('status', 'accepted');
            })->count();

            $user->badge = $this->generateBadge($info ?? null);
            $user->is_connected = true;
        });

        if ($users->isEmpty()) {
            return $this->successResponse([], 'No accepted connections found.');
        }
        return $this->successResponse($users, 'Accepted connections retrieved successfully.');
    }

    private function generateBadge($info)
    {
        if (!$info) return null;
        if ($info->job_title && $info->company_name) return 'Pro';
        if ($info->job_title) return 'Specialist';
        return null;
    }
}
