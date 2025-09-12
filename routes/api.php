<?php

use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\StaticContentController as AdminStaticContentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\CacheController as AdminCacheController;
use App\Http\Controllers\Front\MenuController as FrontMenuController;
use App\Http\Controllers\Front\ContentController as FrontContentController;
use App\Http\Controllers\Front\StaticContentController as FrontStaticContentController;
use App\Http\Controllers\Front\ContactController as FrontContactController;
use App\Http\Controllers\Front\ProjectController as FrontProjectController;
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
        Route::post('/cache/clear', [AdminCacheController::class, 'clear'])->name('cache.clear');
        Route::get('/users/profile', [AdminUserController::class, 'profileUser'])->name('user.profile-user');
        Route::put('/users/profile', [AdminUserController::class, 'profile'])->name('user.profile');

        Route::middleware(['superadmin'])->group(function () {
            Route::group(['middleware' => ['permission:create-users|update-users|view-users|delete-users']], function () {
                Route::post('/users/status/{user}', [AdminUserController::class, 'toggleStatus'])->name('user.toggle-status');
                Route::apiResource('users', AdminUserController::class);
            });

            Route::group(['middleware' => ['permission:view-permissions|create-roles|update-roles|view-roles|delete-roles']], function () {
                Route::post('/roles/assign', [AdminRoleController::class, 'assignRole'])->name('roles.assign-role');
                Route::apiResource('roles', AdminRoleController::class);
                Route::post('/roles/permissions', [AdminRoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
                Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.all');
                Route::post('/permissions', [AdminPermissionController::class, 'createPermissions'])->name('permissions.create-permissions');
            });
        });

        Route::group(['middleware' => ['permission:create-menu|update-menu|view-menu|delete-menu']], function () {
            Route::post('/menu/status/{menu}', [AdminMenuController::class, 'toggleStatus'])->name('menu.toggle-status');
            Route::get('/menu/parent', [AdminMenuController::class, 'parentMenus'])->name('menu.parent-menus');
            Route::get('/menu/submenu/{id}', [AdminMenuController::class, 'subMenus'])->name('menu.submenus');
            Route::post('menu/order/{menu}/up', [AdminMenuController::class, 'orderUp']);
            Route::post('menu/order/{menu}/down', [AdminMenuController::class, 'orderDown']);
            Route::apiResource('menu', AdminMenuController::class);
        });

        Route::group(['middleware' => ['permission:create-content|update-content|view-content|delete-content']], function () {
            Route::post('/content/status/{content}', [AdminContentController::class, 'toggleStatus'])->name('content.toggle-status');
            Route::apiResource('content', AdminContentController::class);
        });

        Route::group(['middleware' => ['permission:create-project|update-project|view-project|delete-project']], function () {
            Route::post('/project/status/{project}', [AdminProjectController::class, 'toggleStatus'])->name('project.toggle-status');
            Route::post('/project/{project}', [AdminProjectController::class, 'update'])->name('project.update');
            Route::apiResource('project', AdminProjectController::class);
        });

        Route::group(['middleware' => ['permission:create-static-content|update-static-content|view-static-content|delete-static-content']], function () {
            Route::post('/static_content/status/{static_content}', [AdminStaticContentController::class, 'toggleStatus'])->name('static-content.toggle-status');
            Route::apiResource('static_content', AdminStaticContentController::class);
        });

        Route::group(['middleware' => ['permission:create-settings|update-settings|view-settings|delete-settings']], function () {
            Route::apiResource('settings', AdminSettingController::class);
        });

        Route::group(['middleware' => ['permission:create-project|update-project|delete-project']], function () {
            Route::prefix('files')->group(function () {
                Route::get('{entity}/{entityId}', [FileController::class, 'index']);
                Route::delete('{entity}/{entityId}', [FileController::class, 'destroy']);
            });
        });
    });
});
Route::get('/projects', [FrontProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [FrontProjectController::class, 'projectByUrl'])->name('projects.project-by-url');
Route::get('/menu/tree', [FrontMenuController::class, 'treeMenus'])->name('menu.tree');
Route::get('/static_content/{name}', [FrontStaticContentController::class, 'contentByName'])->name('static-content.content-by-name');
Route::post('/static_content', [FrontStaticContentController::class, 'contentByNames'])->name('static-content.content-by-names');
Route::post('/contacts', [FrontContactController::class, 'send'])->name('contacts.send');
Route::get('/{slug}', [FrontContentController::class, 'contentByMenu'])->name('content.content-by-menu');
