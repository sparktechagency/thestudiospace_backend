<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class AppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase($notifiable)
    {
        return $this->data;
    }

    // public function toFcm($notifiable)
    // {
    //     return FcmMessage::create()
    //         ->setData($this->data)
    //         ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
    //             ->setTitle($this->data['title'] ?? 'Notification')
    //             ->setBody($this->data['message'] ?? '')
    //         );
    // }
}
