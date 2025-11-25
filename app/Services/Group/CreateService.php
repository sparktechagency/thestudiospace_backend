<?php

namespace App\Services\Group;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateService
{
    use ResponseHelper;

    public function createGroup(array $data)
    {
        $memberIds   = Arr::pull($data, 'member_id', []);
        $bannerImage = Arr::pull($data, 'banner_image');
        $logoImage   = Arr::pull($data, 'logo_image');

        return DB::transaction(function () use ($data, $memberIds, $bannerImage, $logoImage) {

            // Handle file uploads
            if ($bannerImage) {
                $path = $bannerImage->store('group_banners', 'public');
                $data['banner_image'] = 'storage/' . $path;
            }

            if ($logoImage) {
                $path = $logoImage->store('group_logos', 'public');
                $data['logo_image'] = 'storage/' . $path;
            }

            // Create group
            $userId = auth()->id();
            $data['user_id'] = $userId;
            $group = Group::create($data);

            // Add creator as a member
            GroupMember::create([
                'group_id' => $group->id,
                'user_id'  => $userId,
            ]);

            // Send invitations and notifications
            if (!empty($memberIds)) {
                $inviteeIds = array_diff(array_unique($memberIds), [$userId]);

                $invitationsToInsert = collect($inviteeIds)
                    ->map(fn($id) => [
                        'group_id'   => $group->id,
                        'inviter_id' => $userId,
                        'invitee_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                    ->toArray();

                if (!empty($invitationsToInsert)) {
                    GroupInvitation::insert($invitationsToInsert);

                    // Push notifications
                    $users = User::whereIn('id', $inviteeIds)->whereNotNull('fcm_token')->get();
                    $notificationService = new NotificationService();
                    foreach ($users as $user) {
                        $notificationService->send($user, [
                            'title' => 'New Group Invitation',
                            'message' => auth()->user()->name . " invited you to join the group '{$group->name}'.",
                            'type' => 'group_invitation',
                            'group_id' => $group->id,
                            'inviter_id' => $userId,
                        ]);
                    }
                }
            }

            $group->load('members.user');

            return $this->successResponse($group, 'Group created successfully.');
        });
    }
}
