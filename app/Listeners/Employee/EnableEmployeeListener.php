<?php

namespace App\Listeners\Employee;

use App\Events\Employee\EnableEmployeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnableEmployeeListener implements ShouldQueue
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
     * @param  \App\Events\Employee\EnableEmployeeEvent  $event
     * @return void
     */
    public function handle(EnableEmployeeEvent $event)
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
