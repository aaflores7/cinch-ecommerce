<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailController;

Route::get('/', function () {
    return ['service' => 'Email Service', 'status' => 'OK'];
});

Route::prefix('api')->group(function () {
    Route::post('send-order-confirmation', [EmailController::class, 'sendOrderConfirmation']);
});