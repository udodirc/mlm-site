<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('users', UserController::class);
});
