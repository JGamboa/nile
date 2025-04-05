<?php

use Illuminate\Support\Facades\Route;
use Nile\LaravelServer\Http\Controllers\Auth\SignupController;
use Nile\LaravelServer\Http\Controllers\AuthController;
use Nile\LaravelServer\Http\Controllers\TenantController;
use Nile\LaravelServer\Http\Controllers\SQLController;
use Nile\LaravelServer\Http\Controllers\UserTenantController;
use Nile\LaravelServer\Http\Controllers\Auth\OAuthCallbackController;
use Nile\LaravelServer\Http\Controllers\Auth\CsrfController;
use Nile\LaravelServer\Http\Controllers\Auth\AuthErrorController;
use Nile\LaravelServer\Http\Controllers\Auth\PasswordResetController;
use Nile\LaravelServer\Http\Controllers\Auth\SessionController;
use Nile\LaravelServer\Http\Controllers\Auth\LoginController;
use Nile\LaravelServer\Http\Controllers\Auth\LogoutController;
use Nile\LaravelServer\Http\Controllers\Auth\VerificationController;



Route::prefix('api/nile')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);

    Route::post('tenants', [TenantController::class, 'create']);
    Route::get('tenants', [TenantController::class, 'list']);

    Route::get(
        'users/{userId}/tenants',
        [UserTenantController::class, 'list']
    );

    Route::post('sql', [SQLController::class, 'run'])->middleware('nile.context');

    Route::prefix('auth')->group(function () {
        Route::get('callback', [OAuthCallbackController::class, 'handle']);

        Route::get('csrf', [CsrfController::class, 'token']);

        Route::get('error', [AuthErrorController::class, 'show']);

        Route::post('password-reset', [PasswordResetController::class, 'request']);
        Route::post('password-reset/confirm', [PasswordResetController::class, 'confirm']);

        Route::get('session', [SessionController::class, 'show']);
        Route::delete('session', [SessionController::class, 'destroy']);

        Route::post('signin', [LoginController::class, 'login']);
        Route::post('signout', [LogoutController::class, 'logout']);

        Route::get('verify-request', [VerificationController::class, 'verify']);


        Route::post('signup', [SignupController::class, 'register']);
    });

})->middleware('api');


