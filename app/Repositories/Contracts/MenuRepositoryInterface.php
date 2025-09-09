<?php

namespace App\Repositories\Contracts;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface extends BaseRepositoryInterface
{
    public function subMenus(int $id): Collection;

    public function parentMenus(): Collection;

    public function treeMenus(): array;

    public function orderUp(Menu $menu): bool;

    public function orderDown(Menu $menu): bool;
}
