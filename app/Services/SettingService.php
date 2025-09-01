<?php

namespace App\Services;

use App\Data\Admin\Setting\SettingCreateData;
use App\Data\Admin\Setting\SettingUpdateData;
use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Spatie\LaravelData\Data;

/**
 * @extends BaseService<SettingRepositoryInterface, SettingCreateData, SettingUpdateData, Setting>
 */
class SettingService extends BaseService
{
    public function __construct(SettingRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var SettingCreateData $data */
        return [
            'name' => $data->name,
            'key' => $data->key,
            'value' => $data->value
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var SettingUpdateData $data */
        return [
            'name' => $data->name,
            'key' => $data->key,
            'value' => $data->value
        ];
    }
}
