<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Models\StaticContent;

class StaticContentTest extends BaseTest
{
    public function testCreateStaticContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentCreate->value);

        $this->performAction(
            action: 'create',
            route: 'static_content.store',
            data: [
                'name' => 'Test',
                'content' => 'Some content',
            ],
            table: 'static_content',
            expectedJson: ['name' => 'Test', 'content' => 'Some content']
        );
    }

    public function testUpdateStaticContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentUpdate->value);

        $content = StaticContent::factory()->create();

        $this->performAction(
            action: 'update',
            route: 'static_content.update',
            id: $content->id,
            data: [
                'name' => 'Updated name',
                'content' => 'Updated content',
                'status' => false,
            ],
            table: 'static_content',
            expectedJson: [
                'name' => 'Updated name',
                'content' => 'Updated content',
                'status' => false
            ]
        );
    }

    public function testDeleteContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentDelete->value);

        $content = StaticContent::factory()->create();

        $this->performAction(
            action: 'delete',
            route: 'static_content.destroy',
            id: $content->id,
            table: 'static_content'
        );
    }

    public function testContentsList(): void
    {
        $this->auth(PermissionsEnum::StaticContentView->value);

        StaticContent::factory()->count(3)->create();

        $this->performAction(
            action: 'list',
            route: 'static_content.index',
            expectedCount: 3
        );
    }

    public function testSingleContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentView->value);

        $content = StaticContent::factory()->create();

        $this->performAction(
            action: 'show',
            route: 'static_content.show',
            id: $content->id,
            expectedJson: ['content' => $content->content]
        );
    }
}
