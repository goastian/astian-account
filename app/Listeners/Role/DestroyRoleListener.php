<?php

namespace App\Listeners\Role;

use App\Events\Role\DestroyRoleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyRoleListener implements ShouldQueue
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
    public function handle(DestroyRoleEvent $event): void
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
