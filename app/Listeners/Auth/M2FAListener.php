<?php

namespace App\Listeners\Auth;

use App\Events\Auth\M2FAEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class M2FAListener implements ShouldQueue
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
    public function handle(M2FAEvent $event): void
    {
        //
    }
}
