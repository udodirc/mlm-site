<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function clear(): bool
    {
        return Cache::flush();
    }
}
