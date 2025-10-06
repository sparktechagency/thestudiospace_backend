<?php

namespace App\Services\Message;

use App\Models\Chat;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class ChatListService
{
    use ResponseHelper;
    public function chatList($request)
    {
        $user = Auth::user();
            if (!$user) {
                return $this->errorResponse("User not found.");
            }
            $search = $request->input('search', '');
            $query = Chat::with(['receiver:id,name,email,avatar'])->where('receiver_id', auth()->id())
            ->orWhere('sender_id',auth()->id());
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhereHas('sender', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                      $q->orWhereHas('receiver', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
                });
            }
            $chats = $query->get();
            if ($chats->isNotEmpty()) {
                return $this->successResponse($chats, 'Chats retrieved successfully.');
            } else {
                return $this->successResponse([], 'No chats found.');
        }
    }
}
