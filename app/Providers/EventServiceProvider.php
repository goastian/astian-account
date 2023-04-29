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

        //Login
        \App\Events\Auth\LoginEvent::class => [
            \App\Listeners\Auth\LoginListener::class
        ],

        //Token events
        \App\Events\Token\StoreTokenEvent::class => [
            \App\Listeners\Token\StoreTokenListener::class
        ],

        \App\Events\Token\DestroyTokenEvent::class => [
            \App\Listeners\Token\DestroyTokenListener::class
        ],

        \App\Events\Token\DestroyAllTokenEvent::class => [
            \App\Listeners\Token\DestroyAllTokenListener::class
        ],

        //Events CategoryController Assets
        \App\Events\Asset\StoreCategoryEvent::class => [
            \App\Listeners\Asset\StoreCategoryListener::class
        ],

        \App\Events\Asset\UpdateCategoryEvent::class => [
            \App\Listeners\Asset\UpdateCategoryListener::class
        ],

        \App\Events\Asset\DisableCategoryEvent::class => [
            \App\Listeners\Asset\DisableCategoryListener::class
        ],

        \App\Events\Asset\EnableCategoryEvent::class => [
            \App\Listeners\Asset\EnableCategoryListener::class
        ],

        \App\Events\Asset\DestroyCategoryEvent::class => [
            \App\Listeners\Asset\DestroyCategoryListener::class
        ],


        //Events CategoryCalendarController Assets
        \App\Events\Asset\StoreCategoryCalendarEvent::class => [
            \App\Listeners\Asset\StoreCategoryCalendarListener::class
        ],

        \App\Events\Asset\UpdateCategoryCalendarEvent::class => [
            \App\Listeners\Asset\UpdateCategoryCalendarListener::class
        ],

        //Events RoomController Assets
        \App\Events\Asset\StoreRoomEvent::class => [
            \App\Listeners\Asset\StoreRoomListener::class
        ],

        \App\Events\Asset\UpdateRoomEvent::class => [
            \App\Listeners\Asset\UpdateRoomListener::class
        ],

        \App\Events\Asset\EnableRoomEvent::class => [
            \App\Listeners\Asset\EnableRoomListener::class
        ],

        \App\Events\Asset\DisableRoomEvent::class => [
            \App\Listeners\Asset\DisableRoomListener::class
        ],

        \App\Events\Asset\DestroyRoomEvent::class => [
            \App\Listeners\Asset\DestroyRoomListener::class
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
