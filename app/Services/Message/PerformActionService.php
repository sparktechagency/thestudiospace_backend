<?php

namespace App\Services\Message;

use App\Models\Action;
use App\Models\Chat;
use App\Traits\ResponseHelper;

class PerformActionService
{
    use ResponseHelper;
   public function performActions($data, $chat_id)
    {
        $chat = Chat::find($chat_id);
        if (!$chat) {
            return $this->errorResponse("Chat not found.");
        }
        $actionRecord = Action::create([
            'chat_id' => $chat_id,
            'action' => $data['action'],
        ]);
        $action = $data['action'];

        if ($action === 'Pin') {
            $chat->status = 'Pinned';
        } elseif ($action === 'Mute') {
            $chat->status = 'Muted';
        } elseif ($action === 'Block') {
            $chat->status = 'Blocked';
        } elseif ($action === 'Delete') {
            $chat->delete();
            return $this->successResponse('Chat deleted successfully.');
        }
        $chat->save();
        return $this->successResponse("Action '$action' performed successfully.");
    }
}
