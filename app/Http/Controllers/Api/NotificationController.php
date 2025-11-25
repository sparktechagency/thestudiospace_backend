<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Notification\GetService;
use Illuminate\Http\Request;
use App\Services\Notification\MarkAsAllReadService;
use App\Services\Notification\MarkAsReadService;

class NotificationController extends Controller
{
    protected $getService;
    protected $markAsReadService;
    protected $markAllReadService;

    public function __construct(
        GetService $getService,
        MarkAsReadService $markAsReadService,
        MarkAsAllReadService $markAllReadService
    ){
        $this->getService = $getService;
        $this->markAsReadService = $markAsReadService;
        $this->markAllReadService = $markAllReadService;
    }

    // 🔹 Get all notifications
    public function index(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->getService->index($request);
        });
    }

    // 🔹 Get unread notifications
    // public function unread(Request $request)
    // {
    //     return $this->execute(function () use ($request) {
    //         return $this->getService->executeUnread($request);
    //     });
    // }

    // 🔹 Mark one notification as read
    public function markAsRead($id)
    {
        return $this->execute(function () use ($id) {
            return $this->markAsReadService->markAsRead($id);
        });
    }

    // 🔹 Mark all notifications as read
    public function markAllRead()
    {
        return $this->execute(function () {
            return $this->markAllReadService->markAllRead();
        });
    }
}
