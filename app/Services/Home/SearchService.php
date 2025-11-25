<?php

namespace App\Services\Home;

use App\Models\Conection;
use App\Models\User;
use App\Models\Group;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SearchService
{
    use ResponseHelper;

    public function search($request)
    {
        $keyword = $request->keyword ?? null;
        $authUser = Auth::user();

        /* ----------------------------------------------------------
         *  SEARCH USERS + USER INFOS (only users with userInfo)
         * ---------------------------------------------------------- */
        $users = User::with(['userInfo'])
            ->whereHas('userInfo') // ✅ exclude users without userInfo
            ->where('name', 'like', "%$keyword%")
            ->orWhereHas('userInfo', function ($q) use ($keyword) {
                $q->where('job_title', 'like', "%$keyword%")
                  ->orWhere('company_name', 'like', "%$keyword%");
            })
            ->limit(20)
            ->get();

        $users->each(function ($user) use ($authUser) {

            $info = $user->userInfo;

            // Title / Company formatting
            $user->designation = $info->job_title
                ? $info->job_title . " at " . ($info->company_name ?? '')
                : null;

            // Mutual connections
            $user->mutual_connections = Conection::where('user_id', $authUser->id)
                ->where('connection_id', $user->id)
                ->count();

            // Badge logic
            $user->badge = $this->generateBadge($info);

            // Is connected?
            $user->is_connected = Conection::where([
                ['user_id', $authUser->id],
                ['connection_id', $user->id],
            ])->exists();
        });

        /* ----------------------------------------------------------
         *  SEARCH GROUPS
         * ---------------------------------------------------------- */
        $groups = Group::with(['art'])
            ->where('name', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%")
            ->limit(20)
            ->get();

        $groups->each(function ($group) use ($authUser) {

            $group->category = $group->art->name ?? null;
            $group->logo = $group->logo_image;
            $group->banner = $group->banner_image;

            $group->mutual_joined = $group->members()
                ->whereIn('user_id', $authUser->connections()->pluck('connection_id'))
                ->count();

            $group->is_joined = $group->members()->where('user_id', $authUser->id)->exists();
        });

        /* ----------------------------------------------------------
         *  FINAL RESPONSE
         * ---------------------------------------------------------- */
        return $this->successResponse([
            'users'  => $users,
            'groups' => $groups,
        ], "Search results retrieved.");
    }

    protected function generateBadge($info)
    {
        if (!$info) return null;

        if ($info->company_name) {
            return "Works at " . $info->company_name;
        }

        if ($info->job_title) {
            return "Similar interests";
        }

        return null;
    }
}
