<?php

use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::group([
        'middleware' => 'api'
    ], function ($router) {
        Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('auth.logout');
        Route::post('refresh', [AdminAuthController::class, 'refresh'])->name('auth.refresh-token');
        Route::post('me', [AdminAuthController::class, 'me'])->name('auth.me');

        Route::group(['middleware' => ['permission:create-users|update-users|view-users|delete-users']], function () {
            Route::apiResource('users', AdminUserController::class);
        });

        Route::group(['middleware' => ['permission:create-menu|update-menu|view-menu|delete-menu']], function () {
            Route::apiResource('menu', AdminMenuController::class);
        });

        Route::group(['middleware' => ['permission:view-permissions|create-roles|update-roles|view-roles|delete-roles']], function () {
            Route::post('/roles/assign', [AdminRoleController::class, 'assignRole'])->name('roles.assign-role');
            Route::apiResource('roles', AdminRoleController::class);
            Route::post('/roles/permissions', [AdminRoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
            Route::post('/permissions', [AdminPermissionController::class, 'createPermissions'])->name('permissions.create-permissions');
        });
    });
});
