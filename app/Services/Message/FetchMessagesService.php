<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\Message;
use App\Traits\ResponseHelper;
class FetchMessagesService
{
   use ResponseHelper;

   public function fetchMessages($chat_id)
   {
      $chat = Chat::find($chat_id);
      if (!$chat) {
          return $this->errorResponse('Chat not found.');
      }
      $authId = auth()->id();
      if (!in_array($authId, [$chat->sender_id, $chat->receiver_id])) {
          return $this->errorResponse('Unauthorized access.');
      }
      $messages = Message::with(['sender:id,name,email,avatar'])->where('chat_id', $chat_id)
                         ->orderBy('created_at', 'asc')
                         ->paginate(20);
      $messages->getCollection()->transform(function ($message) use ($authId) {
          $message->is_send = $message->sender_id == $authId;
          return $message;
      });
      return $this->successResponse($messages, 'Messages retrieved successfully.');
   }
}
