<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', [LoginController::class, 'store'])->name('login');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('users', AdminUserController::class);
    });
});
