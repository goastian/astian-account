<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginListener
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
}
