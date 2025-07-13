<?php

namespace Tests\Unit;

use App\Data\Admin\Menu\MenuCreateData;
use App\Data\Admin\Menu\MenuUpdateData;
use App\Enums\PermissionsEnum;
use App\Models\Menu;
use App\Models\User;
use App\Repositories\MenuRepository;
use App\Services\MenuService;
use Illuminate\Database\Eloquent\Collection;

class MenuTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return MenuService::class;
    }

    protected function getRepositoryClass(): string
    {
        return MenuRepository::class;
    }

    public function testCreateMenu(): void
    {
        $dto = new MenuCreateData(
            parent_id: null,
            name: 'Menu'
        );

        $menu = new Menu([
            'parent_id' => null,
            'name' => 'Menu'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'parent_id' => null,
                'name' => 'Menu'
            ],
            expectedModel: $menu
        );
    }

    public function testUpdateMenu(): void
    {
        $dto = new MenuUpdateData(
            parent_id: null,
            name: 'Updated Menu'
        );

        $menu = new Menu([
            'parent_id' => null,
            'name' => 'Menu'
        ]);

        $menu->parent_id = null;
        $menu->name = 'Updated Menu';

        $this->assertUpdateEntity(
            model: $menu,
            updateDto: $dto,
            expectedInput: [
                'parent_id' => null,
                'name' => 'Updated Menu'
            ],
            expectedModel: $menu
        );
    }

    public function testDeleteMenu(): void
    {
        $menu = new Menu([
            'id' => 1,
            'parent_id' => null,
            'name' => 'Menu'
        ]);

        $this->assertDeleteEntity(
            model: $menu
        );
    }

    public function testListMenus(): void
    {
        $menus = new Collection([
            new Menu([
                'id' => 1,
                'parent_id' => null,
                'name' => 'Menu'
            ]),
            new Menu([
                'id' => 2,
                'parent_id' => null,
                'name' => 'Menu2'
            ]),
        ]);

        $this->assertListItemsEntity(
            model: $menus,
            items: ['Menu', 'Menu2']
        );
    }

    public function testShowMenu(): void
    {
        $menu = new Menu([
            'id' => 1,
            'parent_id' => null,
            'name' => 'Main Menu',
        ]);
        $menu->exists = true;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($menu);

        /** @var Menu $result */
        $result = $this->service->find(1);

        $this->assertEquals(1, $result->id);
        $this->assertEquals('Main Menu', $result->name);
        $this->assertNull($result->parent_id);
    }
}
