<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FcmNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fcmTokens;
    public $title;
    public $body;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($fcmTokens = [], $title = 'Notification title', $body = 'Notification body', $data = [])
    {
        $this->fcmTokens = $fcmTokens;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
