<?php

namespace App\Services;

use App\Repositories\Contracts\PermissionRepositoryInterface;
use Spatie\LaravelData\Data;

class PermissionService extends BaseService
{
    public function __construct(PermissionRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        return [];
    }

    protected function toUpdateArray(Data $data): array
    {
        return [];
    }

    public function createPermissions()
    {
        return $this->repository->createPermissions();
    }
}
