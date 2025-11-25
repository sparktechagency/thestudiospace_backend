<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;

class GetOrCreateChatService
{
    use ResponseHelper;

    public function getOrCreateChat($receiver_id)
    {
        $authId = auth()->id();

        if ($authId == $receiver_id) {
            return $this->errorResponse('You cannot chat with yourself.');
        }

        $sender = User::find($authId);
        $receiver = User::find($receiver_id);

        if (!$sender || !$receiver) {
            return $this->errorResponse('One or both users do not exist.');
        }

        // Check existing chat
        $chat = Chat::whereIn('sender_id', [$authId, $receiver_id])
                    ->whereIn('receiver_id', [$authId, $receiver_id])
                    ->first();

        // If chat doesn't exist, create it
        if (!$chat) {
            $chat = Chat::create([
                'sender_id' => $authId,
                'receiver_id' => $receiver_id,
            ]);

            // -------------------------
            // Send Notification to Receiver
            // -------------------------
            if ($receiver->fcm_token) {
                $notificationService = new NotificationService();
                $notificationService->send($receiver, [
                    'title' => 'New Chat Started',
                    'message' => "{$sender->name} started a chat with you.",
                    'type' => 'chat_started',
                    'chat_id' => $chat->id,
                    'sender_id' => $authId,
                ]);
            }
        }

        return $this->successResponse($chat, 'Chat session retrieved/created successfully.');
    }
}
