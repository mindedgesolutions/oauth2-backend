<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FactoryController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth/validate-credentials', [AuthController::class, 'validateCredentials']);

Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::get('me', 'me');
        Route::post('logout', 'logout');
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('factories', FactoryController::class);
    Route::controller(MessageController::class)->prefix('messages')->group(function () {
        Route::post('send', 'send');
        Route::get('{userId}', 'conversation');
    });
});
