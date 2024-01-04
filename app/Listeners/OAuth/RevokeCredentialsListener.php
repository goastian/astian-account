<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\RevokeCredentialsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class RevokeCredentialsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RevokeCredentialsEvent $event): void
    {
        //
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'events';
    }
}
