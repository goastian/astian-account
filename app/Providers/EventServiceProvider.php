<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        //login
        \App\Events\Auth\LoginEvent::class => [
            \App\Listeners\Auth\LoginListener::class,
        ],

         \App\Events\Auth\LogoutEvent::class => [
            \App\Listeners\Auth\LogoutListener::class,
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

        \App\Events\Employee\DestroyEmployeeEvent::class => [
            \App\Listeners\Employee\DestroyEmployeeListener::class,
        ],

        \App\Events\Employee\StoreEmployeeRoleEvent::class => [
            \App\Listeners\Employee\StoreEmployeeRoleListener::class,
        ],

        \App\Events\Employee\DestroyEmployeeRoleEvent::class => [
            \App\Listeners\Employee\DestroyEmployeeRoleListener::class,
        ],

        //roles
        \App\Events\Role\StoreRoleEvent::class => [
            \App\Listeners\Role\StoreRoleListener::class,
        ],

        \App\Events\Role\UpdateRoleEvent::class => [
            \App\Listeners\Role\UpdateRoleListener::class,
        ],

        \App\Events\Role\DestroyRoleEvent::class => [
            \App\Listeners\Role\DestroyRoleListener::class,
        ],

        //reesset tokens
        \App\Events\OAuth\RevokeCredentialsEvent::class => [
            \App\Listeners\OAuth\RevokeCredentialsListener::class,
        ],

        //broadcast
        \App\Events\Broadcast\StoreBroadcastEvent::class => [
            \App\Listeners\Broadcast\StoreBroadcastListener::class,
        ],

        \App\Events\Broadcast\DestroyBroadcastEvent::class => [
            \App\Listeners\Broadcast\DestroyBroadcastListener::class,
        ],

        //clients
        \App\Events\Client\StoreClientEvent::class => [
            \App\Listeners\Client\StoreClientListener::class
        ],

        \App\Events\Client\RemoveSessionEvent::class => [
            \App\Listeners\Client\RemoveSessionListener::class
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
