<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Services\Message\ChatListService;
use App\Services\Message\FetchMessagesService;
use App\Services\Message\GetOrCreateChatService;
use App\Services\Message\MarkMessagesAsReadService;
use App\Services\Message\SendMessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $chatListService;
    protected $fetchMessagesService;
    protected $GetOrCreateChatService;
    protected $MarkMessagesAsReadService;
    protected $SendMessageService;
    public function __construct(
        ChatListService $chatListService,
        FetchMessagesService $fetchMessagesService,
        GetOrCreateChatService $getOrCreateChatService,
        MarkMessagesAsReadService $markMessagesAsReadService,
        SendMessageService $sendMessageService,
    ){
        $this->chatListService = $chatListService;
        $this->fetchMessagesService = $fetchMessagesService;
        $this->GetOrCreateChatService = $getOrCreateChatService;
        $this->MarkMessagesAsReadService = $markMessagesAsReadService;
        $this->SendMessageService = $sendMessageService;
    }
    public function chatList()
    {

    }
    public function getOrCreateChat($receiver_id)
    {
        return $this->execute(function()use($receiver_id){
            return $this->GetOrCreateChatService->getOrCreateChat($receiver_id);
        });
    }
    public function sendMessage()
    {

    }
    public function fetchMessages()
    {

    }
    public function markMessagesAsRead()
    {

    }
}
