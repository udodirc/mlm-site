<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use App\Services\SettingsManager;

class LoadAdminSettings
{
    public function handle($request, Closure $next)
    {
        if (!Config::get('app.settings')) {
            SettingsManager::load();
        }

        return $next($request);
    }
}
