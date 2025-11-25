<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Auth;

class MarkAsAllReadService
{
    public function markAllRead()
    {
        $user = Auth::user();
        if ($user->unreadNotifications->isNotEmpty()) {
            $user->unreadNotifications->markAsRead();
            return response()->json([
                'message' => 'All notifications marked as read.',
                'status' => true
            ]);
        }
        return response()->json([
            'message' => 'No unread notifications found.',
            'status' => false
        ]);
    }
}
