<?php

namespace App\Events\OAuth;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RevokeCredentialsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user = null;
    public $socket = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user = null)
    {
        $this->user = $user;
        $this->socket = uniqid();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(env('CHANNEL_NAME', 'auth') . "." . $this->user['data']['id']);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'RevokeCredentialsEvent';
    }
}
