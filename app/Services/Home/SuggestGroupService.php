<?php

namespace App\Services\Home;

use App\Models\Group;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SuggestGroupService
{
    use ResponseHelper;

    /**
     * Suggest groups to the authenticated user
     */
    public function suggestGroup($request)
    {
        $user = Auth::user();

        // Get public groups user is not a member of
        $groups = Group::where('group_type', 'public')
            ->whereDoesntHave('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user:id,name', 'art:id,name') // eager load user & art
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        if ($groups->isEmpty()) {
            return $this->successResponse([], 'No group suggestions found.');
        }

        return $this->successResponse($groups, 'Group suggestions retrieved successfully.');
    }
}
