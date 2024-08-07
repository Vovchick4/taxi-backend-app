<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\ClientCarClassesController;
use App\Http\Controllers\Client\ClientOrderController;
use App\Http\Controllers\Driver\DriverOrderController;

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
    Route::post('/update-user', [AuthController::class, 'updateUser'])->middleware('auth.phone');
    Route::post('/logout', [AuthController::class, 'logoutUser'])->middleware('auth.phone');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/send-code', [AuthController::class, 'sendCode']);

    Route::get('/verify-call', [AuthController::class, 'verifyCall']);
    Route::post('/resend-call', [AuthController::class, 'resendCall']);
    Route::post('/approve-call', [AuthController::class, 'approveCall']);

    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::get('/get-codes', [AuthController::class, 'getCodes']);
});

Route::group(['prefix' => 'driver'], function () {

    Route::group(['prefix' => 'order', 'middleware' => 'auth.phone'], function () {
        Route::get('/get', [DriverOrderController::class, 'getNewOrders']);
        Route::get('/get-all', [DriverOrderController::class, 'getOrders']);
        Route::post('/accept/{orderId}', [DriverOrderController::class, 'acceptOrder']);
    });
});

Route::group(['prefix' => 'client'], function () {

    Route::group(['prefix' => 'car-class', 'middleware' => 'auth.phone'], function () {
        Route::get('/get', [ClientCarClassesController::class, 'getCarsTypes']);
    });

    Route::group(['prefix' => 'order', 'middleware' => 'auth.phone'], function () {
        Route::get('/get/{orderId}', [ClientOrderController::class, 'getOrderById']);
        Route::get('/get-all', [ClientOrderController::class, 'getOrders']);
        Route::post('/create', [ClientOrderController::class, 'create']);
    });
});
