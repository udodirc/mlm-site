<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null): mixed
    {
        return Cache::remember(
            "setting:{$key}",
            now()->addMinutes(10),
            fn() => Setting::query()->where('key', $key)->value('value') ?? $default
        );
    }
}
