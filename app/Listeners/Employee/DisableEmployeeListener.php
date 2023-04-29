<?php

namespace App\Listeners\Employee;

use App\Events\Employee\DisableEmployeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DisableEmployeeListener
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
     * @param  \App\Events\Employee\DisableEmployeeEvent  $event
     * @return void
     */
    public function handle(DisableEmployeeEvent $event)
    {
        //
    }
}
