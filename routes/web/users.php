<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Account\CodeController;
use App\Http\Controllers\Web\Account\UserController;
use App\Http\Controllers\Web\Account\HomePageController;
use App\Http\Controllers\Web\Auth\RegisterClientController;
use App\Http\Controllers\Web\Account\NotificationController;
use App\Http\Controllers\Web\Account\UserNotificationController;
use App\Http\Controllers\Web\Account\UserSubscriptionController;

Route::group([
    'prefix' => 'account',
    'as' => 'users.',
], function () {

    Route::get("/", [HomePageController::class, 'dashboard'])->name('dashboard');

    Route::get("/update", [UserController::class, 'profile'])->name('profile');
    Route::put("/update", [UserController::class, 'personalInformation'])->name('update');
    Route::get("/change-password", [UserController::class, 'formToChangePassword'])->name("password");
    Route::put("/change-password", [UserController::class, 'changePassword'])->name('change.password');

    Route::get('/verify/account', [RegisterClientController::class, 'verifyAccount'])->name('verify.account');
    Route::get('/verified-account', [RegisterClientController::class, 'verifiedAccount'])->name('verified.account');

    Route::get('/check-my-account', [RegisterClientController::class, 'formVerifyAccount'])->name('check.account');
    Route::post('/send-verification-email', [RegisterClientController::class, 'sendVerificationEmail'])->name('verification.email');

    Route::get('/verify/2fa-factor', [CodeController::class, 'create'])->name('2fa.send-code');
    Route::post('/verify/2fa-factor', [CodeController::class, 'loginBy2FA'])->name('2fa.login');
    Route::get('/two-factor-authentication', [CodeController::class, 'formToRequestToken'])->name('2fa.request');
    Route::post('/m2fa/authorize', [CodeController::class, 'requestToken2FA'])->name('2fa.authorize');
    Route::post('/m2fa/activate', [CodeController::class, 'factor2faEnableOrDisable'])->name('2fa.activate');

    Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/buy', [UserSubscriptionController::class, 'buy'])->name('subscriptions.buy');
    Route::post('/subscriptions/renew', [UserSubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::put('/subscriptions/cancel/{transaction_id}', [UserSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::get('/subscriptions/checkout/success', [UserSubscriptionController::class, 'success'])->name('checkout.success');

    Route::put('/packages/{package_id}/recurring', [UserSubscriptionController::class, 'recurringPayment'])->name('recurring.payment');

    Route::prefix('notifications')
        ->as('notification.')
        ->group(function () {

            Route::get(
                '/',
                [NotificationController::class, 'listAllNotifications']
            )->name('index');

            Route::get(
                '/unread',
                [NotificationController::class, 'listUnreadNotifications']
            )->name('unread');

            Route::get(
                '/{notification_id}',
                [NotificationController::class, 'show']
            )->name('show');

            Route::post(
                '/mark-as-read/{notification_id}',
                [NotificationController::class, 'markAsReadNotification']
            )->name('mark-as-read');

            Route::post(
                '/mark-all-as-read',
                [NotificationController::class, 'markAsReadAllNotifications']
            )->name('mark-all-as-read');

            Route::delete(
                '/destroy-all',
                [NotificationController::class, 'destroyNotifications']
            )->name('destroy-all');
        });
});