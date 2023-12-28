<?php

namespace App\Listeners\Auth;
 
use Illuminate\Queue\InteractsWithQueue;
use Elyerr\ApiResponse\Events\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Auth\LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        //
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return env('REDIS_QUEUE_EVENTS', 'events');
    }
    
}
