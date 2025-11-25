<?php

namespace App\Services\User;

use App\Models\Conection;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class AcceptConnectionService
{
    use ResponseHelper;

    public function acceptUserConnection($connection_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        // Find the connection request
        $connection = Conection::where('user_id', $connection_id)
                               ->where('connection_id', $user->id)
                               ->first();

        if (!$connection) {
            return $this->errorResponse("Connection request not found.");
        }

        if ($connection->status === 'accepted') {
            return $this->successResponse([], "Connection already accepted.");
        }

        // Accept the connection
        $connection->status = 'accepted';
        $connection->save();

        // -----------------------------
        // Notify the user who sent the request
        // -----------------------------
        $senderUser = User::find($connection_id);
        if ($senderUser) {
            $notificationData = [
                'title' => 'Connection Accepted',
                'message' => $user->name . ' accepted your connection request.',
                'type' => 'connection_accepted',
                // 'link' => '/connections', // optional link
            ];

            $notificationService = new NotificationService();
            $notificationService->send($senderUser, $notificationData);
        }
        // -----------------------------

        return $this->successResponse([], "Connection accepted successfully.");
    }
}
