<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Observers\SettingsObserver;
use App\Observers\UserObserver;
use App\Repositories\ContentRepository;
use App\Repositories\Contracts\ContentRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\StaticContentRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\MenuRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use App\Repositories\StaticContentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(StaticContentRepositoryInterface::class, StaticContentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Setting::observe(SettingsObserver::class);
    }
}
