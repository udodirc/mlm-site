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
                'title' => 'title',
                'meta_description' => 'meta_description',
                'meta_keywords' => 'meta_keywords',
                'og_title' =>'og_title',
                'og_description' => 'og_description',
                'og_image' => 'og_image',
                'og_type' => 'og_type',
                'og_url' => 'og_url',
                'canonical_url' => 'canonical_url',
                'robots' => 'robots'
            ],
            table: 'content',
            expectedJson: [
                'content' => 'Some content',
                'title' => 'title',
                'meta_description' => 'meta_description',
                'meta_keywords' => 'meta_keywords',
                'og_title' =>'og_title',
                'og_description' => 'og_description',
                'og_image' => 'og_image',
                'og_type' => 'og_type',
                'og_url' => 'og_url',
                'canonical_url' => 'canonical_url',
                'robots' => 'robots'
            ]
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
                'title' => 'Updated title',
                'meta_description' => 'Updated meta_description',
                'meta_keywords' => 'Updated meta_keywords',
                'og_title' =>'Updated og_title',
                'og_description' => 'Updated og_description',
                'og_image' => 'Updated og_image',
                'og_type' => 'Updated og_type',
                'og_url' => 'Updated og_url',
                'canonical_url' => 'Updated canonical_url',
                'robots' => 'Updated robots'
            ],
            table: 'content',
            expectedJson: [
                'content' => 'Updated content',
                'status' => false,
                'title' => 'Updated title',
                'meta_description' => 'Updated meta_description',
                'meta_keywords' => 'Updated meta_keywords',
                'og_title' =>'Updated og_title',
                'og_description' => 'Updated og_description',
                'og_image' => 'Updated og_image',
                'og_type' => 'Updated og_type',
                'og_url' => 'Updated og_url',
                'canonical_url' => 'Updated canonical_url',
                'robots' => 'Updated robots'
            ]
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
