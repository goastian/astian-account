<?php

namespace App\Listeners\Employee;

use App\Events\Employee\StoreEmployeeRoleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreEmployeeRoleListener implements ShouldQueue
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
     * @param  \App\Events\Employee\StoreEmployeeRoleEvent  $event
     * @return void
     */
    public function handle(StoreEmployeeRoleEvent $event)
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
