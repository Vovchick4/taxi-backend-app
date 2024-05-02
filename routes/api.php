<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {

    Route::get('/me', [AuthController::class, 'getUser'])->middleware('auth.phone');
    Route::post('/send-code', [AuthController::class, 'sendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
});

Route::group(['prefix' => 'driver'], function () {

    Route::group(['prefix' => 'order', 'middleware' => 'auth.phone'], function () {
    });
});

Route::group(['prefix' => 'client'], function () {

    Route::group(['prefix' => 'order', 'middleware' => 'auth.phone'], function () {
    });
});
