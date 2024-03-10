<?php

use Illuminate\Support\Facades\Route;

// Route::apiResource('users', App\Http\Controllers\API\AuthController::class);

Route::prefix('turbo')
    ->name('users.')
    ->namespace("App\Http\Controllers\API")
    ->group(function () {
        // Route::get('/users', 'AuthController@index')->name('index');
        Route::get('/users', [App\Http\Controllers\API\AuthController::class, 'index'])
            ->name('index')
            ->withoutMiddleware('auth');
        Route::get('/users/{user}', [App\Http\Controllers\API\AuthController::class, 'show'])
            ->name('show')
            ->withoutMiddleware('auth')
            ->whereNumber('user');
        Route::post('/users', [App\Http\Controllers\API\AuthController::class, 'store'])->name('store');
        Route::patch('/users/{user}', [App\Http\Controllers\API\AuthController::class, 'update'])->name('update');
        Route::delete('/users/{user}', [App\Http\Controllers\API\AuthController::class, 'destroy'])->name('destroy');
    });
