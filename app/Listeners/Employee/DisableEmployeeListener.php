<?php

namespace App\Listeners\Employee;

use App\Events\Employee\DisableEmployeeEvent;
use App\Notifications\Auth\UserDisableNotification;
use App\Notifications\Client\ClientDisableNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DisableEmployeeListener implements ShouldQueue
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
        
    }

   /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'events';
    }
}
