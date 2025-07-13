<?php

namespace Feature;

use App\Enums\PermissionsEnum;
use App\Models\Menu;
use Illuminate\Http\Response;
use Tests\Feature\BaseTest;

class MenuTest extends BaseTest
{
    public function testCreateMenu(): void
    {
        $this->auth(PermissionsEnum::MenuCreate->value);

        $data = [
            'parent_id' => null,
            'name' => 'Menu',
        ];

        $response = $this->postJson(route('menu.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('menu', ['name' => 'Menu']);

        $response->assertJsonFragment(['name' => 'Menu']);
    }

    public function testCreateChildMenu(): void
    {
        $this->auth(PermissionsEnum::MenuCreate->value);

        $parent = Menu::factory()->create();

        $data = [
            'name' => 'Sub Menu',
            'parent_id' => $parent->id,
        ];

        $response = $this->postJson(route('menu.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            'name' => 'Sub Menu',
            'parent_id' => $parent->id,
        ]);

        $this->assertDatabaseHas('menu', [
            'name' => 'Sub Menu',
            'parent_id' => $parent->id,
        ]);
    }

    public function testUpdateMenu(): void
    {
        $this->auth(PermissionsEnum::MenuUpdate->value);

        $menu = Menu::factory()->create();

        $data = [
            'parent_id' => null,
            'name' => 'Updated menu',
        ];

        $response = $this->putJson(route('menu.update', $menu->id), $data);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonFragment(['name' => 'Updated menu']);
    }

    public function testDeleteMenu(): void
    {
        $user = $this->auth(PermissionsEnum::MenuDelete->value);

        $menu = Menu::factory()->create();

        $response = $this->deleteJson(route('menu.destroy', $menu));

        $response->assertStatus(200);
    }

    public function testMenusList(): void
    {
        $this->auth(PermissionsEnum::MenuView->value);

        Menu::factory()->count(3)->create();

        $response = $this->getJson(route('menu.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
    }

    public function testSingleMenu(): void
    {
        $this->auth(PermissionsEnum::MenuView->value);

        $menu = Menu::factory()->create();

        $response = $this->getJson(route('menu.show', $menu->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $menu->name]);
    }
}
