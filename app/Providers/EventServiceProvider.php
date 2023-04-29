<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        \App\Events\Auth\LoginEvent::class => [
            \App\Listeners\Auth\LoginListener::class
        ],

        \App\Events\Token\StoreTokenEvent::class => [
            \App\Listeners\Token\StoreTokenListener::class
        ],

        \App\Events\Token\DestroyTokenEvent::class => [
            \App\Listeners\Token\DestroyTokenListener::class
        ],

        \App\Events\Token\DestroyAllTokenEvent::class => [
            \App\Listeners\Token\DestroyAllTokenListener::class
        ],

        //Employee 
        \App\Events\Employee\StoreEmployeeEvent::class => [
            \App\Listeners\Employee\StoreEmployeeListener::class,
        ], 

        \App\Events\Employee\UpdateEmployeeEvent::class => [
            \App\Listeners\Employee\UpdateEmployeeListener::class,
        ],

        \App\Events\Employee\DisableEmployeeEvent::class => [
            \App\Listeners\Employee\DisableEmployeeListener::class,
        ],

        \App\Events\Employee\EnableEmployeeEvent::class => [
            \App\Listeners\Employee\EnableEmployeeListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
