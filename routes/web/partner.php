<?php

use App\Http\Controllers\Web\Account\PartnerController;

Route::group([
    'prefix' => 'partner',
    'as' => 'partners.'
], function () {

    Route::get('dashboard', [PartnerController::class, 'dashboard'])->name('dashboard');
    Route::get('sales', [PartnerController::class, 'sales'])->name('sales');
    Route::get('generate', [PartnerController::class, 'show'])->name('show');
    Route::post('generate', [PartnerController::class, 'generate'])->name('generate');
});