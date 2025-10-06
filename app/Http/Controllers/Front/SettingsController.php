<?php

namespace App\Http\Controllers\Front;

use App\Data\Front\Settings\SettingsByKeysData;
use App\Http\Controllers\Controller;
use App\Resource\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SettingsController extends Controller
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function settingsByKeys(SettingsByKeysData $keys): JsonResponse|AnonymousResourceCollection
    {
        $settings = $this->service->getSettingsByKeys($keys);

        if ($settings->isEmpty()) {
            return response()->json(['message' => 'Settings not found'], 404);
        }

        return (SettingResource::class)::collection(
            $settings
        );
    }
}
