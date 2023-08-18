<?php

namespace App\Listeners\Accounting;

use App\Events\Accounting\StoreAccountingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreAccountingListener
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
     * @param  \App\Events\Accounting\StoreAccountingEvent  $event
     * @return void
     */
    public function handle(StoreAccountingEvent $event)
    {
        //
    }
}
