<?php

namespace App\Listeners\Employee;

use App\Events\Employee\StoreEmployeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreEmployeeListener implements ShouldQueue
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
     * @param  \App\Events\Employee\StoreEmployeeEvent  $event
     * @return void
     */
    public function handle(StoreEmployeeEvent $event)
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
