<?php

namespace App\Providers;

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
 
        //Events CategoryController Assets
        \App\Events\Asset\Category\StoreCategoryEvent::class => [
            \App\Listeners\Asset\Category\StoreCategoryListener::class,
        ],

        \App\Events\Asset\Category\UpdateCategoryEvent::class => [
            \App\Listeners\Asset\Category\UpdateCategoryListener::class,
        ],

        \App\Events\Asset\Category\DisableCategoryEvent::class => [
            \App\Listeners\Asset\Category\DisableCategoryListener::class,
        ],

        \App\Events\Asset\Category\EnableCategoryEvent::class => [
            \App\Listeners\Asset\Category\EnableCategoryListener::class,
        ],

        \App\Events\Asset\Category\DestroyCategoryEvent::class => [
            \App\Listeners\Asset\Category\DestroyCategoryListener::class,
        ],

        //Events CategoryCalendarController Assets
        \App\Events\Asset\Category\Calendar\StoreCategoryCalendarEvent::class => [
            \App\Listeners\Asset\Category\Calendar\StoreCategoryCalendarListener::class,
        ],

        \App\Events\Asset\Category\Calendar\UpdateCategoryCalendarEvent::class => [
            \App\Listeners\Asset\Category\Calendar\UpdateCategoryCalendarListener::class,
        ],

        //Events RoomController Assets
        \App\Events\Asset\Room\StoreRoomEvent::class => [
            \App\Listeners\Asset\Room\StoreRoomListener::class,
        ],

        \App\Events\Asset\Room\UpdateRoomEvent::class => [
            \App\Listeners\Asset\Room\UpdateRoomListener::class,
        ],

        \App\Events\Asset\Room\EnableRoomEvent::class => [
            \App\Listeners\Asset\Room\EnableRoomListener::class,
        ],

        \App\Events\Asset\Room\DisableRoomEvent::class => [
            \App\Listeners\Asset\Room\DisableRoomListener::class,
        ],

        \App\Events\Asset\Room\DestroyRoomEvent::class => [
            \App\Listeners\Asset\Room\DestroyRoomListener::class,
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

        \App\Events\Employee\StoreEmployeeRoleEvent::class => [
            \App\Listeners\Employee\StoreEmployeeRoleListener::class,
        ],

        \App\Events\Employee\DestroyEmployeeRoleEvent::class => [
            \App\Listeners\Employee\DestroyEmployeeRoleListener::class,
        ],

        //booking
        \App\Events\Booking\StoreBookingEvent::class => [
            \App\Listeners\Booking\StoreBookingListener::class,
        ],

        \App\Events\Booking\UpdateBookingEvent::class => [
            \App\Listeners\Booking\UpdateBookingListener::class,
        ],

        \App\Events\Booking\DeleteBookingEvent::class => [
            \App\Listeners\Booking\DeleteBookingListener::class,
        ],

        \App\Events\Booking\Rooms\StoreBookingRoomEvent::class => [
            \App\Listeners\Booking\Rooms\StoreBookingRoomListener::class,
        ],

        \App\Events\Booking\Rooms\UpdateBookingRoomEvent::class => [
            \App\Listeners\Booking\Rooms\UpdateBookingRoomListener::class,
        ],

        \App\Events\Booking\Rooms\DestroyBookingRoomEvent::class => [
            \App\Listeners\Booking\Rooms\DestroyBookingRoomListener::class,
        ],

        \App\Events\Booking\company\StoreBookingCompanyEvent::class => [
            \App\Listeners\Booking\company\StoreBookingCompanyListener::class,
        ],

        \App\Events\Booking\company\UpdateBookingCompanyEvent::class => [
            \App\Listeners\Booking\company\UpdateBookingCompanyListener::class,
        ],

        \App\Events\Booking\Client\StoreBookingRoomClientEvent::class => [
            \App\Listeners\Booking\Client\StoreBookingRoomClientListener::class,
        ],

        \App\Events\Booking\Client\UpdateBookingRoomClientEvent::class => [
            \App\Listeners\Booking\Client\UpdateBookingRoomClientListener::class,
        ],

        \App\Events\Booking\Client\DestroyBookingRoomClientEvent::class => [
            \App\Listeners\Booking\Client\DestroyBookingRoomClientListener::class,
        ],

        \App\Events\Booking\Payments\StoreBookingPaymentEvent::class => [
            \App\Listeners\Booking\Payments\StoreBookingPaymentListener::class,
        ],

        \App\Events\Booking\Payments\UpdateBookingPaymentEvent::class => [
            \App\Listeners\Booking\Payments\UpdateBookingPaymentListener::class,
        ],

        //accountings
        \App\Events\Accounting\StoreAccountingEvent::class => [
            \App\Listeners\Accounting\StoreAccountingListener::class,
        ],

        \App\Events\Accounting\UpdateAccountingEvent::class => [
            \App\Listeners\Accounting\UpdateAccountingListener::class,
        ],

        //Reservation
        \App\Events\Reservation\StoreReservationEvent::class => [
            \App\Listeners\Reservation\StoreReservationListener::class,
        ],

        \App\Events\Reservation\UpdateReservationEvent::class => [
            \App\Listeners\Reservation\UpdateReservationListener::class,
        ],

        \App\Events\Reservation\DestroyReservationEvent::class => [
            \App\Listeners\Reservation\DestroyReservationListener::class,
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
