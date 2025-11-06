<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin')->group(function () {
    // Új munka létrehozása
    Route::post('/deliveries', [DeliveryController::class, 'store']);

    // Munka módosítása
    Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);

    // Munka törlése
    Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy']);

    // Munka fuvarozóhoz rendelése
    Route::put('/deliveries/{id}/assign', [DeliveryController::class, 'assignCarrier']);
});
