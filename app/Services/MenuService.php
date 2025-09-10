<?php

namespace App\Services;

use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
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
            'name' => $data->name,
            'url' => $data->url,
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var MenuUpdateData $data */
        return [
            'parent_id' => $data->parent_id,
            'name' => $data->name,
            'url' => $data->url,
            'status' => $data->status,
        ];
    }

    public function subMenus(int $id): Collection
    {
        return $this->repository->subMenus($id);
    }

    public function parentMenus(): Collection
    {
        return $this->repository->parentMenus();
    }

    public function treeMenus(): array
    {
        return $this->repository->treeMenus();
    }

    public function orderUp(Menu $menu): bool
    {
        return $this->repository->orderUp($menu);
    }

    public function orderDown(Menu $menu): bool
    {
        return $this->repository->orderDown($menu);
    }
}
