<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender;
    public $sender_photo;
    public $link;
    public $notification_id;
    public $channel;

    public function __construct($message, $sender, $sender_photo, $link, $channel, $notId)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->sender_photo = $sender_photo;
        $this->link = $link;
        $this->notification_id = $notId;
        $this->channel = $channel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notification.' . $this->channel);
    }

    public function broadcastAs()
    {
        return 'notification';
    }
}
