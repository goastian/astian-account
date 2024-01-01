<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\RevokeCredentialsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
}
