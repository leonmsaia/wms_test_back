<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/**
 * -----------------------------------------------------------------------------
 * API Routes
 * -----------------------------------------------------------------------------
 * Registers public auth endpoints and Sanctum-protected user CRUD endpoints.
 *
 * @author  Leon. M. Saia
 * @contact leonmsaia@gmail.com
 * @date    2025-08-12
 */

// Auth (public)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });
});

// Users (protected)
Route::middleware('auth:sanctum')->prefix('usuarios')->group(function () {
    Route::get('/',        [UserController::class, 'index']);
    Route::post('/',       [UserController::class, 'store']);
    Route::put('/{id}',    [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
