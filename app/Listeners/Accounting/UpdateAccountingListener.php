<?php

namespace App\Listeners\Accounting;

use App\Events\Accounting\UpdateAccountingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateAccountingListener
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
     * @param  \App\Events\Accounting\UpdateAccountingEvent  $event
     * @return void
     */
    public function handle(UpdateAccountingEvent $event)
    {
        //
    }
}
