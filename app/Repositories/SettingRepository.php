<?php

namespace App\Repositories;

use App\Data\Front\Settings\SettingsByKeysData;
use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function __construct(Setting $setting)
    {
        parent::__construct($setting);
    }

    public function allInArray(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function getSettingsByKeys(SettingsByKeysData $keys): ?Collection
    {
        return $this->model
            ->whereIn('key', $keys->keys)
            ->get();
    }
}
