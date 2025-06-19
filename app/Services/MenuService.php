<?php

namespace App\Services;

use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Spatie\LaravelData\Data;

/**
 * @extends BaseService<MenuRepositoryInterface, MenuCreateData, MenuUpdateData, Menu>
 */
class MenuService extends BaseService
{
    public function __construct(MenuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var MenuCreateData $data */
        return [
            'parent_id' => $data->parent_id,
            'name' => $data->name
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var MenuUpdateData $data */
        return [
            'parent_id' => $data->parent_id,
            'name' => $data->name
        ];
    }
}
