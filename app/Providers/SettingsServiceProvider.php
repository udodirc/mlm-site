<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(SettingRepositoryInterface $settings)
    {
        $allSettings = Cache::rememberForever('settings', fn () => $settings->allInArray());

        config(['app.settings' => $allSettings]);
    }
}
