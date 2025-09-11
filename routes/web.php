<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('oauth/login', [AuthController::class, 'login'])->withoutMiddleware([
    VerifyCsrfToken::class,
]);
