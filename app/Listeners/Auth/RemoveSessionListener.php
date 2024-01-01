<?php

namespace App\Listeners\Auth;

use App\Events\Auth\RemoveSessionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveSessionListener
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
    public function handle(RemoveSessionEvent $event): void
    {
        //
    }
}
