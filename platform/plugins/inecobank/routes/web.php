<?php

use Botble\InecoBank\Http\Controllers\InecoBankController;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => InecoBankController::class, 'middleware' => ['web', 'core']], function (): void {
    Route::get('payment/inecobank/status', 'getCallback')->name('payments.inecobank.status');
});
