<?php

namespace App\Services\User;

use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use App\Models\Conection;
use App\Models\User;
use App\Services\Notification\NotificationService;

class UserConnectionService
{
   use ResponseHelper;

    public function userConnection($connection_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        if ($user->id == $connection_id) {
            return $this->errorResponse("You cannot connect with yourself.");
        }
        $existingConnection = Conection::where('user_id', $user->id)
                                        ->where('connection_id', $connection_id)
                                        ->exists();
        if ($existingConnection) {
            return $this->successResponse([], "Connection already exists.");
        }
        Conection::create([
            'user_id' => $user->id,
            'connection_id' => $connection_id,
        ]);
        $connectedUser = User::find($connection_id);
        if ($connectedUser) {
            $notificationData = [
                'title' => 'New Connection Request',
                'message' => $user->name . ' wants to connect with you.',
                'type' => 'connection_request',
            ];

            $notificationService = new NotificationService();
            $notificationService->send($connectedUser, $notificationData);
        }
        return $this->successResponse([], "Connection created successfully.");
    }
}
