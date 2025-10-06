<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\ActionRequest;
use App\Http\Requests\Message\SendRequest;
use App\Services\Message\ChatListService;
use App\Services\Message\FetchMessagesService;
use App\Services\Message\GetOrCreateChatService;
use App\Services\Message\MarkMessagesAsReadService;
use App\Services\Message\PerformActionService;
use App\Services\Message\SendMessageService;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $chatListService;
    protected $fetchMessagesService;
    protected $GetOrCreateChatService;
    protected $MarkMessagesAsReadService;
    protected $SendMessageService;
    protected $performActionService;
    public function __construct(
        ChatListService $chatListService,
        FetchMessagesService $fetchMessagesService,
        GetOrCreateChatService $getOrCreateChatService,
        MarkMessagesAsReadService $markMessagesAsReadService,
        SendMessageService $sendMessageService,
        PerformActionService $performActionService,
    ){
        $this->chatListService = $chatListService;
        $this->fetchMessagesService = $fetchMessagesService;
        $this->GetOrCreateChatService = $getOrCreateChatService;
        $this->MarkMessagesAsReadService = $markMessagesAsReadService;
        $this->SendMessageService = $sendMessageService;
        $this->performActionService = $performActionService;
    }
    public function chatList(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->chatListService->chatList($request);
        });
    }
    public function getOrCreateChat($receiver_id)
    {
        return $this->execute(function()use($receiver_id){
            return $this->GetOrCreateChatService->getOrCreateChat($receiver_id);
        });
    }
    public function sendMessage(SendRequest $sendRequest,$chat_id)
    {
        return $this->execute(function()use($sendRequest,$chat_id){
            $data = $sendRequest->validated();
            return $this->SendMessageService->sendMessage($data,$chat_id);
        });
    }
    public function fetchMessages($chat_id)
    {
        return $this->execute(function()use($chat_id){
            return $this->fetchMessagesService->fetchMessages($chat_id);
        });
    }
    public function markMessagesAsRead($chat_id)
    {
        return $this->execute(function()use($chat_id){
            return $this->MarkMessagesAsReadService->markMessagesAsRead($chat_id);
        });
    }
    public function performActions(ActionRequest $actionRequest,$chat_id)
    {
         return $this->execute(function()use($actionRequest,$chat_id){
            $data = $actionRequest->validated();
            return $this->performActionService->performActions($data,$chat_id);
        });
    }

}
