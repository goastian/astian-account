<?php

namespace App\Listeners\Employee;

use App\Events\Employee\EnableEmployeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnableEmployeeListener
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
}
