<?php

namespace App\Listeners\Employee;

use App\Events\Employee\StoreEmployeeRoleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
}
