<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController; // <-- Import the controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return ['service' => 'Catalog Service', 'status' => 'OK'];
});


// --- Define our API routes here ---
Route::prefix('api')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
});