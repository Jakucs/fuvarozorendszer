<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('admin')->group(function () {
    // Új munka létrehozása
    Route::post('/deliveries', [AdminController::class, 'store']);

    // Munka módosítása
    Route::put('/deliveries/{id}', [AdminController::class, 'update']);

    // Munka törlése
    Route::delete('/deliveries/{id}', [AdminController::class, 'destroy']);

    // Munka fuvarozóhoz rendelése
    Route::put('/deliveries/{id}/assign', [AdminController::class, 'assignCarrier']);
});

Route::post('/register', [AuthController::class, 'register']);
