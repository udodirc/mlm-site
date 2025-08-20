<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface extends BaseRepositoryInterface
{
    public function allInArray(): array;
}
