<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Traits\ResponseHelper;

class SendMessageService
{
   use ResponseHelper;
    public function sendMessage($data, $chat_id)
    {
        $chat = Chat::find($chat_id);
        if (!$chat) {
            return $this->errorResponse("Chat not found.");
        }
        if (isset($data['attachment']) && $data['attachment']->isValid()) {
            $path = $data['attachment']->store('chat_attachments', 'public');
            $data['attachment'] = 'storage/' . $path;
        }
        $data['sender_id'] = auth()->id();
        $data['chat_id'] = $chat_id;
        $message = Message::create($data);

        // $recipient = $chat->receiver_id == auth()->id() ? $chat->sender : $chat->receiver;
        return $this->successResponse($message, 'Message sent successfully.');
    }
}
