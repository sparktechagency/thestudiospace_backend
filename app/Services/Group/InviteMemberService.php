<?php

namespace App\Services\Group;

use App\Models\User;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use App\Models\BusinessProfileFollow;
use App\Models\Conection;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class InviteMemberService
{
    use ResponseHelper;

    /**
     * Get eligible users to invite
     */
    public function inviteMember($request)
    {
        $inviterId = Auth::id();
        $groupId = $request->group_id ?? null;

        $followers = BusinessProfileFollow::where('status', 'following')
            ->get(['user_id', 'business_profile_id'])
            ->flatMap(fn($item) => [$item->user_id, $item->business_profile_id])
            ->unique()
            ->values()
            ->toArray();

        $connections = Conection::where(function ($q) use ($inviterId) {
                $q->where('user_id', $inviterId)
                  ->orWhere('connection_id', $inviterId);
            })
            ->where('status', 'accepted')
            ->get(['user_id', 'connection_id'])
            ->flatMap(fn($c) => [$c->user_id, $c->connection_id])
            ->unique()
            ->reject(fn($id) => $id == $inviterId)
            ->values()
            ->toArray();

        $potentialIds = array_unique(array_merge($followers, $connections));

        $alreadyInvited = GroupInvitation::where('inviter_id', $inviterId)
            ->when($groupId, fn($q) => $q->where('group_id', $groupId))
            ->pluck('invitee_id')
            ->toArray();

        $existingMembers = $groupId
            ? GroupMember::where('group_id', $groupId)->pluck('user_id')->toArray()
            : [];

        $excludedIds = array_unique(array_merge($alreadyInvited, $existingMembers, [$inviterId]));

        $eligibleUsers = User::whereIn('id', $potentialIds)
            ->whereNotIn('id', $excludedIds)
            ->where('role', '<>', 'ADMIN')
            ->where('is_banned', false)
            ->paginate($request->per_page ?? 20);

        return $this->successResponse($eligibleUsers, 'Eligible members retrieved successfully.');
    }

    /**
     * Actually invite members and send notifications
     */
    public function sendInvitations($groupId, array $inviteeIds)
    {
        $inviterId = Auth::id();

        // Remove duplicates and inviter themselves
        $inviteeIds = array_diff(array_unique($inviteeIds), [$inviterId]);

        // Filter out already invited or existing members
        $alreadyInvited = GroupInvitation::where('inviter_id', $inviterId)
            ->where('group_id', $groupId)
            ->pluck('invitee_id')
            ->toArray();

        $existingMembers = GroupMember::where('group_id', $groupId)
            ->pluck('user_id')
            ->toArray();

        $finalInvitees = array_diff($inviteeIds, array_merge($alreadyInvited, $existingMembers));

        if (empty($finalInvitees)) {
            return $this->errorResponse('No new members to invite.');
        }

        $invitations = collect($finalInvitees)
            ->map(fn($id) => [
                'group_id'   => $groupId,
                'inviter_id' => $inviterId,
                'invitee_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->toArray();

        GroupInvitation::insert($invitations);

        // Send push notifications
        $users = User::whereIn('id', $finalInvitees)->whereNotNull('fcm_token')->get();
        $notificationService = new NotificationService();
        foreach ($users as $user) {
            $notificationService->send($user, [
                'title' => 'Group Invitation',
                'message' => auth()->user()->name . " invited you to join the group.",
                'type' => 'group_invitation',
                'group_id' => $groupId,
                'inviter_id' => $inviterId,
            ]);
        }

        return $this->successResponse($finalInvitees, 'Members invited successfully.');
    }
}
