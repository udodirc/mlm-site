<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Repositories\Contracts\SettingRepositoryInterface;

class LoadAdminSettings
{
    public function handle($request, Closure $next)
    {
        if (!Config::get('app.settings')) {
            $settings = Cache::rememberForever('admin_settings', function () {
                return app(SettingRepositoryInterface::class)->allInArray();
            });

            Config::set('app.settings', $settings);

        }

        return $next($request);
    }
}
