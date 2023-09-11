<?php

use App\Enum\EnumType;
use App\Http\Controllers\Account\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Asset\RoomController;
use App\Http\Controllers\User\ClientController;
use App\Http\Controllers\User\UserRoleController;
use App\Http\Controllers\Asset\CalendarController;
use App\Http\Controllers\Asset\CategoryController;
use App\Http\Controllers\Sanctum\TokensController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Auth\AuthorizationController;
use App\Http\Controllers\Booking\BookingRentController;
use App\Http\Controllers\Asset\CategoryCalendarController;
use App\Http\Controllers\Booking\BookingCompanyController;
use App\Http\Controllers\Booking\BookingPaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Booking\BookingExtraChargeController;
use App\Http\Controllers\Booking\BookingRentClientController;
use App\Http\Controllers\Booking\PaymentController;
use App\Http\Controllers\Reservation\ReservationController;

//Valores absolutos
Route::get('payment-type', [EnumType::class, 'payment_type']);
Route::get('payment-method', [EnumType::class, 'payment_method']);
Route::get('booking-type', [EnumType::class, 'booking_type']);
Route::get('document-type', [EnumType::class, 'documento_type']);

//Auth
Route::post('login', [AuthorizationController::class,'store']);
Route::post('logout', [AuthorizationController::class, 'destroy']);
Route::get('about', [AuthenticatedSessionController::class, 'profile']);

//Tokens
Route::get('tokens', [TokensController::class, 'index'])->name('tokens.index');
Route::post('tokens', [TokensController::class, 'store'])->name('tokens.store');
Route::delete('tokens/clean', [TokensController::class, 'destroyAllTokens'])->name('tokens.clean');
Route::delete('tokens/{id}', [TokensController::class, 'destroy'])->name('tokens.destroy');

//Roles
Route::resource('roles', RoleController::class)->only('index');

//Employees
Route::delete('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
Route::get('users/{id}/enable', [UserController::class, 'enable'])->name('users.enable');
Route::resource('users', UserController::class)->except('edit', 'create', 'destroy');
Route::resource('users.roles', UserRoleController::class)->only('index', 'store', 'destroy');

//categories
Route::delete('categories/{category}/disable', [CategoryController::class, 'disable'])->name('categories.disable');
Route::get('categories/{id}/enable', [CategoryController::class, 'enable'])->name('categories.enable');
Route::resource('categories', CategoryController::class)->except('edit','create');
Route::resource('categories.calendars', CategoryCalendarController::class)->except('edit', 'create','destroy');

//rooms
Route::delete('rooms/{room}/disable', [RoomController::class, 'disable'])->name('rooms.disable');
Route::get('rooms/{id}/enable', [RoomController::class, 'enable'])->name('rooms.enable');
Route::resource('rooms', RoomController::class)->except('edit', 'create');
 
//calendar
Route::resource('calendars', CalendarController::class)->only('index');

//Payments
Route::resource('payments', PaymentController::class)->only('index');

//booking
Route::get('activate-rooms', [BookingController::class, 'activate_rooms'])->name('activate-rooms');
Route::resource('booking', BookingController::class)->except('edit', 'create');
Route::resource('booking.payments', BookingPaymentController::class)->only('index', 'store','update');
Route::resource('booking.charges', BookingExtraChargeController::class)->only('index', 'store', 'destroy');
Route::resource('booking.company', BookingCompanyController::class)->only('index', 'store');
Route::resource('booking.rents', BookingRentController::class)->except('edit', 'create');
Route::resource('booking.rents.huespeds', BookingRentClientController::class)->only('index', 'store', 'destroy');

Route::resource('reservations', ReservationController::class)->only('store');

//Clientes
Route::resource('clients', ClientController::class)->only('index', 'show','update');


//Data Ingresos y egresos
Route::resource('accounting', AccountController::class)->only('index', 'store', 'update');