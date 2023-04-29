<?php

namespace App\Listeners\Employee;

use App\Events\Employee\StoreEmployeeEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreEmployeeListener
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
}
