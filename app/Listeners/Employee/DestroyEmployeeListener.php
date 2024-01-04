<?php

namespace App\Listeners\Employee;

use App\Events\Employee\DestroyEmployeeEvent;
use App\Notifications\Client\DestroyClientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyEmployeeListener implements ShouldQueue
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
    public function handle(DestroyEmployeeEvent $event): void
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
