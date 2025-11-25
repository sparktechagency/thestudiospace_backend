<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Auth;

class MarkAsReadService
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Notification not found.',
        ], 404);
    }
}
