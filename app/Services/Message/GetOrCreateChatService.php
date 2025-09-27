<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Models\User;
use App\Traits\ResponseHelper;

class GetOrCreateChatService
{
    use ResponseHelper;
    public function getOrCreateChat($receiver_id)
    {
        $authId = auth()->id();
        if ($authId == $receiver_id) {
            return $this->errorResponse('You cannot chat with yourself.', 400);
        }
        $senderExists = User::find($authId);
        $receiverExists = User::find($receiver_id);
        if (!$senderExists || !$receiverExists) {
            return $this->errorResponse('One or both users do not exist.', 400);
        }
        $chat = Chat::whereIn('sender_id', [$authId, $receiver_id])
                    ->whereIn('receiver_id', [$authId, $receiver_id])
                    ->first();
        if (!$chat) {
            $chat = Chat::create([
                'sender_id' => $authId,
                'receiver_id' => $receiver_id,
            ]);
        }
        return $this->successResponse($chat, 'Chat session retrieved/created successfully.');
    }
}
