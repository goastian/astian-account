<?php

namespace App\Listeners\Auth;
 
use App\Events\Auth\LogoutEvent;
use Illuminate\Queue\InteractsWithQueue; 
use Illuminate\Contracts\Queue\ShouldQueue;

class LogoutListener implements ShouldQueue
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
     * @param  \App\Events\Auth\LogoutEvent  $event
     * @return void
     */
    public function handle(LogoutEvent $event)
    {
        //
    }
}
