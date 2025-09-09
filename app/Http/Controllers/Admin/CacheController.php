<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;

class CacheController extends Controller
{
    protected CacheService $service;

    public function __construct(CacheService $service)
    {
        $this->service = $service;
    }


    public function clear(): bool
    {
        return $this->service->clear();
    }
}
