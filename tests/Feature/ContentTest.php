<?php

namespace Feature;

use App\Enums\PermissionsEnum;
use App\Models\Content;
use App\Models\Menu;
use Illuminate\Http\Response;
use Tests\Feature\BaseTest;

class ContentTest extends BaseTest
{
    public function testCreateContent(): void
    {
        $this->auth(PermissionsEnum::ContentCreate->value);

        $menu = Menu::factory()->create();

        $data = [
            'menu_id' => $menu->id,
            'content' => 'Some content',
        ];

        $response = $this->postJson(route('content.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('content', ['content' => 'Some content', 'menu_id' => $menu->id]);
        $response->assertJsonFragment(['content' => 'Some content']);
    }

    public function testUpdateContent(): void
    {
        $this->auth(PermissionsEnum::ContentUpdate->value);

        $content = Content::factory()->create();

        $data = ['content' => 'Updated content', 'menu_id' => $content->menu_id, 'status' => false];

        $response = $this->putJson(route('content.update', $content->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['content' => 'Updated content']);
        $response->assertJsonFragment(['status' => false]);
    }


    public function testDeleteContent(): void
    {
        $this->auth(PermissionsEnum::ContentDelete->value);

        $content = Content::factory()->create();

        $response = $this->deleteJson(route('content.destroy', $content->id));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('content', ['id' => $content->id]);
    }

    public function testContentsList(): void
    {
        $this->auth(PermissionsEnum::ContentView->value);

        Content::factory()->count(3)->create();

        $response = $this->getJson(route('content.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
    }

    public function testSingleContent(): void
    {
        $this->auth(PermissionsEnum::ContentView->value);

        $content = Content::factory()->create();

        $response = $this->getJson(route('content.show', $content->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['content' => $content->content]);
    }
}
