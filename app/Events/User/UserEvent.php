<?php

namespace App\Events\User;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Channel name
     * @var 
     */
    public $channel;

    /**
     * Name of event
     * @var 
     */
    public $event;

    /**
     * Message for event
     * @var 
     */
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($channel, $event, $message = null)
    {
        $this->event = $event;
        $this->channel = $channel;
        $this->message = $message;
        $this->socket = request()->header('x-socket-id');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->channel),
        ];
    }

    /**
     * Name of event
     */
    public function broadcastAs()
    {
        return $this->event;
    }
}
