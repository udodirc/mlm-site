<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\SettingsManager;

class SettingsObserver
{
    public function created(Setting $settings): void
    {
        SettingsManager::reload();
    }

    public function updated(Setting $settings): void
    {
        SettingsManager::reload();
    }

    public function deleted(Setting $settings): void
    {
        SettingsManager::reload();
    }
}
