<?php

use Illuminate\Support\Facades\Route; 
use App\Services\Payment\Webhook\StripeWebhookController;


Route::group([
    'prefix' => 'webhook',
    'as' => 'webhook.'
], function () {

    Route::post('/stripe', [StripeWebhookController::class, 'handle']);

});