<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Models\Content;
use App\Models\Menu;

class ContentTest extends BaseTest
{
    public function testCreateContent(): void
    {
        $this->auth(PermissionsEnum::ContentCreate->value);

        $menu = Menu::factory()->create();

        $this->performAction(
            action: 'create',
            route: 'content.store',
            data: [
                'menu_id' => $menu->id,
                'content' => 'Some content',
            ],
            table: 'content',
            expectedJson: ['content' => 'Some content']
        );
    }

    public function testUpdateContent(): void
    {
        $this->auth(PermissionsEnum::ContentUpdate->value);

        $content = Content::factory()->create();

        $this->performAction(
            action: 'update',
            route: 'content.update',
            id: $content->id,
            data: [
                'content' => 'Updated content',
                'menu_id' => $content->menu_id,
                'status' => false,
            ],
            table: 'content',
            expectedJson: ['content' => 'Updated content', 'status' => false]
        );
    }

    public function testDeleteContent(): void
    {
        $this->auth(PermissionsEnum::ContentDelete->value);

        $content = Content::factory()->create();

        $this->performAction(
            action: 'delete',
            route: 'content.destroy',
            id: $content->id,
            table: 'content'
        );
    }

    public function testContentsList(): void
    {
        $this->auth(PermissionsEnum::ContentView->value);

        Content::factory()->count(3)->create();

        $this->performAction(
            action: 'list',
            route: 'content.index',
            expectedCount: 3
        );
    }

    public function testSingleContent(): void
    {
        $this->auth(PermissionsEnum::ContentView->value);

        $content = Content::factory()->create();

        $this->performAction(
            action: 'show',
            route: 'content.show',
            id: $content->id,
            expectedJson: ['content' => $content->content]
        );
    }
}
