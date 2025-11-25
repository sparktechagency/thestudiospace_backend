<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Traits\ResponseHelper;

class ViewService
{
    use ResponseHelper;

    public function viewGroup($group_id)
    {
        $perPage = request()->get('per_page', 20); // default 20 members per page

        // Retrieve group with related owner
        $group = Group::with('user:id,name,email')->find($group_id);

        if (!$group) {
            return $this->errorResponse('Group not found.');
        }

        // Paginate accepted invitations (members)
        $acceptedInvitations = GroupInvitation::where('group_id', $group_id)
            ->where('status', 'accepted')
            ->with('invitee:id,name,email,avatar')
            ->paginate($perPage, ['id', 'group_id', 'invitee_id', 'status']);

        // Structure paginated member data
        $members = $acceptedInvitations->getCollection()->pluck('invitee');

        // Combine all in one clean response
        return $this->successResponse([
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'location' => $group->location,
                'banner_image' => $group->banner_image,
                'logo_image' => $group->logo_image,
                'group_type' => $group->group_type,
                'allow_post' => (bool) $group->allow_post,
                'admin_approval' => (bool) $group->admin_approval,
                'owner' => $group->user,
            ],
            'members' => [
                'data' => $members,
                'pagination' => [
                    'total' => $acceptedInvitations->total(),
                    'per_page' => $acceptedInvitations->perPage(),
                    'current_page' => $acceptedInvitations->currentPage(),
                    'last_page' => $acceptedInvitations->lastPage(),
                ],
            ],
        ], 'Group details retrieved successfully.');
    }
}
