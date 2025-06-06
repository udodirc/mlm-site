<?php

namespace App\Services;

use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use Spatie\LaravelData\Data;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

/**
 * @extends BaseService<RoleRepositoryInterface, RoleCreateData, RoleUpdateData, Role>
 */
class RoleService extends BaseService
{
    public function __construct(RoleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var RoleCreateData $data */
        return [
            'name' => $data->name
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var RoleUpdateData $data */
        return [
            'name' => $data->name
        ];
    }
}
