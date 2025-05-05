<?php

use App\Models\Payment\Webhook\StripeWebhookController;


Route::group([
    'prefix' => 'webhook',
    'as' => 'webhook.'
], function () {

    Route::post('/stripe', [StripeWebhookController::class, 'handle']);

});