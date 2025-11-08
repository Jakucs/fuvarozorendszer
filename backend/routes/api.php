<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarrierController;

Route::middleware('auth:sanctum')->group(function () {

    
    Route::get('/carrier/deliveries', [CarrierController::class, 'index']);
    Route::put('/carrier/deliveries/{id}/status', [CarrierController::class, 'updateStatus']);

    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::prefix('admin')->group(function () {

    // Fuvarfeladatok listázása (admin nézet)
    Route::get('/deliveries', [AdminController::class, 'index']);
    
    Route::post('/deliveries', [AdminController::class, 'store']);

    Route::post('/storecarriers', [AdminController::class, 'storeCarrier']);

    Route::put('/deliveries/{id}', [AdminController::class, 'update']);

    Route::delete('/deliveries/{id}', [AdminController::class, 'destroy']);

    Route::put('/deliveries/{id}/assign', [AdminController::class, 'assignCarrier']);

    Route::get('/carriers', [AdminController::class, 'getCarriers']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
