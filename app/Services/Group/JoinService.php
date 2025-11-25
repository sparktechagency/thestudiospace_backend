<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;

class JoinService
{
    use ResponseHelper;

    public function joinGroup($request, $group_id)
    {
        $userId = auth()->id();
        $status = strtolower($request->status); // expect 'accept' or 'decline'

        // 1️⃣ Check if group exists
        $group = Group::find($group_id);
        if (!$group) {
            return $this->errorResponse("Group not found.");
        }

        // 2️⃣ Check if invitation exists
        $invitation = GroupInvitation::where('group_id', $group_id)
            ->where('invitee_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            return $this->errorResponse("No pending invitation found for this group.");
        }

        // 3️⃣ Handle decline
        if ($status === 'decline') {
            $invitation->delete();
            return $this->successResponse([], 'You have declined the group invitation.');
        }

        // 4️⃣ Handle accept
        if ($status === 'accept') {
            // Check if already a member
            $alreadyMember = GroupMember::where('group_id', $group_id)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyMember) {
                return $this->errorResponse("You are already a member of this group.");
            }

            // Create membership
            $member = GroupMember::create([
                'group_id' => $group_id,
                'user_id' => $userId,
            ]);

            // Update invitation status
            $invitation->update(['status' => 'accepted']);

            // 5️⃣ Notify the group creator/admin
            $creator = User::find($group->user_id);
            if ($creator && $creator->fcm_token) {
                $notificationService = new NotificationService();
                $notificationService->send($creator, [
                    'title' => 'New Group Member',
                    'message' => auth()->user()->name . " has joined your group '{$group->name}'.",
                    'type' => 'group_member_join',
                    'group_id' => $group->id,
                    'member_id' => $userId,
                ]);
            }

            return $this->successResponse($member, 'You have successfully joined the group.');
        }

        return $this->errorResponse("Invalid request. Please send 'accept' or 'decline' in status.");
    }
}
