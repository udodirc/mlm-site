<?php

namespace App\Services;

use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsManager
{
    public static function load(): array
    {
        $settings = Cache::rememberForever('app.settings', function () {
            return app(SettingRepositoryInterface::class)->allInArray();
        });

        Config::set('app.settings', $settings);

        return $settings;
    }

    public static function clear(): void
    {
        Cache::forget('app.settings');
    }

    public static function reload(): array
    {
        self::clear();
        return self::load();
    }
}
