<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Models\Menu;

class MenuTest extends BaseTest
{
    public function testCreateMenu(): void
    {
        $this->auth(PermissionsEnum::MenuCreate->value);

        $this->performAction(
            action: 'create',
            route: 'menu.store',
            data: [
                'parent_id' => null,
                'name' => 'Menu',
            ],
            table: 'menu',
            expectedJson: ['name' => 'Menu']
        );
    }

    public function testCreateChildMenu(): void
    {
        $this->auth(PermissionsEnum::MenuCreate->value);

        $parent = Menu::factory()->create();

        $this->performAction(
            action: 'create',
            route: 'menu.store',
            data: [
                'name' => 'Sub Menu',
                'parent_id' => $parent->id,
            ],
            table: 'menu',
            expectedJson: [
                'name' => 'Sub Menu',
                'parent_id' => $parent->id
            ]
        );
    }

    public function testUpdateMenu(): void
    {
        $this->auth(PermissionsEnum::MenuUpdate->value);

        $menu = Menu::factory()->create();

        $this->performAction(
            action: 'update',
            route: 'menu.update',
            id: $menu->id,
            data: [
                'parent_id' => null,
                'name' => 'Updated menu',
                'status' => false
            ],
            table: 'menu',
            expectedJson: [
                'name' => 'Updated menu',
                'status' => false
            ]
        );
    }

    public function testDeleteMenu(): void
    {
        $this->auth(PermissionsEnum::MenuDelete->value);

        $menu = Menu::factory()->create();

        $this->performAction(
            action: 'delete',
            route: 'menu.destroy',
            id: $menu->id,
            table: 'menu'
        );
    }

    public function testMenusList(): void
    {
        $this->auth(PermissionsEnum::MenuView->value);

        Menu::factory()->count(3)->create();

        $this->performAction(
            action: 'list',
            route: 'menu.index',
            expectedCount: 3
        );
    }

    public function testSingleMenu(): void
    {
        $this->auth(PermissionsEnum::MenuView->value);

        $menu = Menu::factory()->create();

        $this->performAction(
            action: 'show',
            route: 'menu.show',
            id: $menu->id,
            expectedJson: ['name' => $menu->name]
        );
    }
}
