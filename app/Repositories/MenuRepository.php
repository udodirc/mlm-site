<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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
            ->whereNull('menu.parent_id')
            ->where('menu.status', true)
            ->leftJoin('content', 'menu.id', '=', 'content.menu_id')
            ->whereNull('content.menu_id')
            ->get(['menu.id', 'menu.name']);
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

    public function orderUp(Menu $menu): bool
    {
        $siblings = $this->siblings($menu);
        $index = $siblings->search(fn($m) => $m->id === $menu->id);

        if ($index === false) {
            return false;
        }

        if ($index > 0) {
            $prev = $siblings[$index - 1];

            [$menu->order, $prev->order] = [$prev->order, $menu->order];

            if ($menu->save() && $prev->save()) {
                $this->cache($menu, $prev);

                return true;
            }

            return false;
        }

        return true;
    }

    public function orderDown(Menu $menu): bool
    {
        $siblings = $this->siblings($menu);
        $index = $siblings->search(fn($m) => $m->id === $menu->id);

        if ($index < $siblings->count() - 1) {
            $next = $siblings[$index + 1];
            [$menu->order, $next->order] = [$next->order, $menu->order];

            if ($menu->save() && $next->save()) {
                $this->cache($menu, $next);

                return true;
            }

            return false;
        }

        return true;
    }

    private function siblings(Menu $menu): Collection
    {
        return $menu->parent
            ? $menu->parent->children()->get()
            : Menu::whereNull('parent_id')->orderBy('order')->get();
    }

    private function cache(Menu $menu, Menu $next): void
    {
        if ($this->isCacheable()) {
            Cache::tags($this->getTag())->put($this->getCacheKey($menu->getKey()), $menu, $this->getTtl());
            Cache::tags($this->getTag())->put($this->getCacheKey($next->getKey()), $next, $this->getTtl());
            $this->clearListCache();
        }
    }
}
