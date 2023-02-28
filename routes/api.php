<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// return the csrf token as a json
Route::get('/csrf', function () {
    return response()->json(['csrf' => csrf_token()]);
});
// get all stores
Route::get('/stores', [StoreController::class, 'index']);
// get all drivers
Route::get('/drivers', [DriverController::class, 'index']);
// get driver by id
Route::get('/drivers/{driver}', [DriverController::class, 'show']);
// get orders without middleware
Route::get('/orders', [OrderController::class, 'index']);
// get order by id
Route::get('/orders/{order}', [OrderController::class, 'show']);
// get orders with middleware
Route::middleware('auth.driver')->get('/ordersByDriver', [OrderController::class, 'indexByDriver']);
// create a new order for a store
Route::post('/stores/{store}/orders', [OrderController::class, 'store']);
// login
Route::post('/login', [AuthController::class, 'login']);
// deliver order
Route::middleware('auth.driver')->put('/orders/{order}/deliver', [OrderController::class, 'deliver']);