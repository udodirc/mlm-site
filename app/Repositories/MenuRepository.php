<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository extends AbstractRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $menu)
    {
        parent::__construct($menu);
    }

    public function subMenus(int $id): Collection
    {
        return $this->model
            ->where('parent_id', $id)
            ->where('status', true)
            ->get(['id', 'name']);
    }

    public function parentMenus(): Collection
    {
        return $this->model
            ->where('parent_id', null)
            ->where('status', true)
            ->get(['id', 'name']);
    }

    public function treeMenus(): array
    {
        $items = $this->model
            ->where('status', true)
            ->get();

        return $this->buildTree($items);
    }

    private function buildTree($items = [], $parentId = null): array
    {
        $branch = [];

        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildTree($items, $item->id);

                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }
}
