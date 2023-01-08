<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LoginTokenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginTokenListener
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
     * @param  \App\Events\Auth\LoginTokenEvent  $event
     * @return void
     */
    public function handle(LoginTokenEvent $event)
    {
        //
    }
}
