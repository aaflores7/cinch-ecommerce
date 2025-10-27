<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController; // Import the controller

Route::get('/', function () {
    return ['service' => 'Checkout Service', 'status' => 'OK'];
});

// --- Define our API routes here ---
Route::prefix('api')->group(function () {
    // We only need the 'store' method to create an order
    Route::apiResource('orders', OrderController::class)->only(['store']);
});