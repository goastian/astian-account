<?php

namespace App\Listeners\Role;

use App\Events\Role\StoreRoleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreRoleListener implements ShouldQueue
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
    public function handle(StoreRoleEvent $event): void
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
