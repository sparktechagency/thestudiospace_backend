<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseHelper;

class SendMessageService
{
    use ResponseHelper;

    public function sendMessage($data, $chat_id)
    {
        $authId = auth()->id();
        $chat = Chat::find($chat_id);

        if (!$chat) {
            return $this->errorResponse("Chat not found.");
        }

        // Handle attachment
        if (isset($data['attachment']) && $data['attachment']->isValid()) {
            $path = $data['attachment']->store('chat_attachments', 'public');
            $data['attachment'] = 'storage/' . $path;
        }

        $data['sender_id'] = $authId;
        $data['chat_id'] = $chat_id;

        $message = Message::create($data);

        // Identify recipient
        $recipientId = $chat->sender_id == $authId ? $chat->receiver_id : $chat->sender_id;
        $recipient = User::find($recipientId);

        // Send notification if recipient exists and has FCM token
        if ($recipient && $recipient->fcm_token) {
            $notificationService = new NotificationService();
            $notificationService->send($recipient, [
                'title' => "New Message",
                'message' => "You have a new message from " . auth()->user()->name,
                'type' => "chat_message",
                'chat_id' => $chat->id,
                'sender_id' => $authId,
                'message_id' => $message->id,
            ]);
        }

        return $this->successResponse($message, 'Message sent successfully.');
    }
}
