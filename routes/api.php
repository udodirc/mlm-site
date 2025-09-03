<?php

use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\StaticContentController as AdminStaticContentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Front\MenuController as FrontMenuController;
use App\Http\Controllers\Front\ContentController as FrontContentController;
use App\Http\Middleware\LoadAdminSettings;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::group([
        'middleware' => ['api', LoadAdminSettings::class]
    ], function ($router) {
        Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('auth.logout');
        Route::post('refresh', [AdminAuthController::class, 'refresh'])->name('auth.refresh-token');
        Route::post('me', [AdminAuthController::class, 'me'])->name('auth.me');

        Route::group(['middleware' => ['permission:create-users|update-users|view-users|delete-users']], function () {
            Route::apiResource('users', AdminUserController::class);
        });

        Route::group(['middleware' => ['permission:create-menu|update-menu|view-menu|delete-menu']], function () {
            Route::get('/menu/parent', [AdminMenuController::class, 'parentMenus'])->name('menu.parent-menus');
            Route::get('/menu/submenu/{id}', [AdminMenuController::class, 'subMenus'])->name('menu.submenus');
            Route::apiResource('menu', AdminMenuController::class);
        });

        Route::group(['middleware' => ['permission:create-content|update-content|view-content|delete-content']], function () {
            Route::apiResource('content', AdminContentController::class);
            Route::apiResource('static_content', AdminStaticContentController::class);
        });

        Route::group(['middleware' => ['permission:create-settings|update-settings|view-settings|delete-settings']], function () {
            Route::apiResource('settings', AdminSettingController::class);
        });

        Route::group(['middleware' => ['permission:view-permissions|create-roles|update-roles|view-roles|delete-roles']], function () {
            Route::post('/roles/assign', [AdminRoleController::class, 'assignRole'])->name('roles.assign-role');
            Route::apiResource('roles', AdminRoleController::class);
            Route::post('/roles/permissions', [AdminRoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
            Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.all');
            Route::post('/permissions', [AdminPermissionController::class, 'createPermissions'])->name('permissions.create-permissions');
        });
    });
});
Route::get('/menu/tree', [FrontMenuController::class, 'treeMenus']);
Route::get('/{slug}', [FrontContentController::class, 'contentByMenu']);
