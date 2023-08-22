<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LogoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogoutListener
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
