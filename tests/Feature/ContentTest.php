<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Jobs\ContentFilesUploadJob;
use App\Models\Content;
use App\Models\Menu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;

class ContentTest extends BaseTest
{
    public function testCreateContentWithOgImage(): void
    {
        $this->auth(PermissionsEnum::ContentCreate->value);

        Queue::fake();

        $menu = Menu::factory()->create();

        $file = UploadedFile::fake()->image('og_image.jpg');

        $data = [
            'menu_id' => $menu->id,
            'content' => 'Some content',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' => 'og_title',
            'og_description' => 'og_description',
            'og_image' => $file,
            'og_type' => 'og_type',
            'og_url' => 'og_url',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots',
        ];

        $response = $this->postJson(route('content.store'), $data);

        $response->assertCreated();

        // Проверяем что job отправился
        Queue::assertPushed(ContentFilesUploadJob::class);

        // Проверяем что запись создалась
        $this->assertDatabaseHas('content', [
            'menu_id' => $menu->id,
            'content' => 'Some content',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' => 'og_title',
            'og_description' => 'og_description',
            'og_type' => 'og_type',
            'og_url' => 'og_url',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots',
        ]);
    }

    public function testUpdateContentWithOgImage(): void
    {
        $this->auth(PermissionsEnum::ContentUpdate->value);

        Queue::fake();

        $content = Content::factory()->create([
            'content' => 'Old content',
            'status' => true,
            'title' => 'Old title',
            'meta_description' => 'Old meta_description',
            'meta_keywords' => 'Old meta_keywords',
            'og_title' => 'Old og_title',
            'og_description' => 'Old og_description',
            'og_image' => 'Old og_image',
            'og_type' => 'Old og_type',
            'og_url' => 'Old og_url',
            'canonical_url' => 'Old canonical_url',
            'robots' => 'Old robots',
        ]);

        $newOgImage = UploadedFile::fake()->image('new_og_image.jpg');

        $data = [
            'menu_id' => $content->menu_id,
            'content' => 'Updated content',
            'status' => false,
            'title' => 'Updated title',
            'meta_description' => 'Updated meta_description',
            'meta_keywords' => 'Updated meta_keywords',
            'og_title' => 'Updated og_title',
            'og_description' => 'Updated og_description',
            'og_image' => $newOgImage,
            'og_type' => 'Updated og_type',
            'og_url' => 'Updated og_url',
            'canonical_url' => 'Updated canonical_url',
            'robots' => 'Updated robots',
        ];

        $response = $this->postJson(
            route('content.update', ['content' => $content->id]),
            $data
        );

        $response->assertOk();
        Queue::assertPushed(ContentFilesUploadJob::class);

        $dataForDb = collect($data)->except(['og_image'])->toArray();
        $dataForDb['id'] = $content->id;

        $this->assertDatabaseHas('content', $dataForDb);
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
