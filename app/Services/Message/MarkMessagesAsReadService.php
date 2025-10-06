<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Traits\ResponseHelper;

class MarkMessagesAsReadService
{
    use ResponseHelper;
    public function markMessagesAsRead($chat_id)
    {
        $authId = auth()->id();
        $chat = Chat::find($chat_id);
        if (!$chat) {
            return $this->errorResponse('Chat not found.');
        }
        $updatedCount = Message::where('chat_id', $chat_id)
            ->where('sender_id', '!=', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        if ($updatedCount === 0) {
            return $this->successResponse([], 'No unread messages to mark as read.');
        }
        return $this->successResponse([], 'Messages marked as read.');
    }
}
