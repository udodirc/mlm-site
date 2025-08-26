<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Setting\SettingCreateData;
use App\Data\Admin\Setting\SettingUpdateData;
use App\Http\Controllers\BaseController;
use App\Models\Setting;
use App\Resource\SettingResource;
use App\Services\SettingService;

/**
 * @extends BaseController<SettingService, Setting, SettingResource, SettingCreateData, SettingUpdateData>
 */
class SettingController extends BaseController
{
    protected bool $paginate = false;
    public function __construct(SettingService $service)
    {
        parent::__construct(
            $service,
            SettingResource::class,
            Setting::class,
            SettingCreateData::class,
            SettingUpdateData::class
        );
    }
}
