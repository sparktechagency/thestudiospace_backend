<?php

namespace App\Services\User;

use App\Models\Conection;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class CancelConnectionService
{
    use ResponseHelper;

    public function cancelUserConnection($connection_id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }

        // Find the connection (either sent or received)
        $connection = Conection::where(function($query) use ($user, $connection_id) {
                                $query->where('user_id', $user->id)
                                      ->where('connection_id', $connection_id);
                            })
                            ->orWhere(function($query) use ($user, $connection_id) {
                                $query->where('user_id', $connection_id)
                                      ->where('connection_id', $user->id);
                            })
                            ->first();

        if (!$connection) {
            return $this->errorResponse("Connection not found.");
        }

        // Delete the connection
        $connection->delete();

        // -----------------------------
        // Optional: Notify the other user
        // -----------------------------
        $otherUserId = $connection->user_id == $user->id ? $connection->connection_id : $connection->user_id;
        $otherUser = User::find($otherUserId);

        if ($otherUser) {
            $notificationData = [
                'title' => 'Connection Cancelled',
                'message' => $user->name . ' has cancelled the connection.',
                'type' => 'connection_cancelled',
                'link' => '/connections', // optional link
            ];

            $notificationService = new NotificationService();
            $notificationService->send($otherUser, $notificationData);
        }
        // -----------------------------

        return $this->successResponse([], "Connection cancelled successfully.");
    }
}
