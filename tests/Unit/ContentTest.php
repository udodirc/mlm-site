<?php

namespace Tests\Unit;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Models\Content;
use App\Models\Menu;
use App\Repositories\ContentRepository;
use App\Services\ContentService;

class ContentTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return ContentService::class;
    }

    protected function getRepositoryClass(): string
    {
        return ContentRepository::class;
    }

    public function testCreateContent(): void
    {
        $menu = Menu::factory()->create();

        $dto = new ContentCreateData(
            $menu->id,
            'Some content',
            'title',
            'meta_description',
            'meta_keywords',
            'og_title',
            'og_description',
            'og_image',
            'og_url',         // правильный порядок: сначала og_url
            'og_type',        // потом og_type
            'canonical_url',
            'robots'
        );

        $content = new Content([
            'menu_id' => 1,
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
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'menu_id' => 1,
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
            expectedModel: $content
        );
    }

    public function testUpdateContent(): void
    {
        $menu = Menu::factory()->create();

        $dto = new ContentUpdateData(
            $menu->id,
            'Updated content',
            status: false,
            title: 'Updated title',
            meta_description: 'Updated meta_description',
            meta_keywords: 'Updated meta_keywords',
            og_title: 'Updated og_title',
            og_description: 'Updated og_description',
            og_image: 'Updated og_image',
            og_url: 'Updated og_url',
            og_type: 'Updated og_type',
            canonical_url: 'Updated canonical_url',
            robots: 'Updated robots'
        );

        $content = new Content([
            'menu_id' => $menu->id,
            'content' => 'Some content',
            'status' => true,
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_url' => 'og_url',
            'og_type' => 'og_type',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots'
        ]);

        $content->menu_id = $menu->id;
        $content->content = 'Updated content';
        $content->status = false;
        $content->title = 'Updated title';
        $content->meta_description = 'Updated meta_description';
        $content->meta_keywords = 'Updated meta_keywords';
        $content->og_title = 'Updated og_title';
        $content->og_description = 'Updated og_description';
        $content->og_image = 'Updated og_image';
        $content->og_url = 'Updated og_url';
        $content->og_type = 'Updated og_type';
        $content->canonical_url = 'Updated canonical_url';
        $content->robots = 'Updated robots';

        $this->assertUpdateEntity(
            model: $content,
            updateDto: $dto,
            expectedInput: [
                'menu_id' => $menu->id,
                'content' => 'Updated content',
                'status' => false,
                'title' => 'Updated title',
                'meta_description' => 'Updated meta_description',
                'meta_keywords' => 'Updated meta_keywords',
                'og_title' =>'Updated og_title',
                'og_description' => 'Updated og_description',
                'og_image' => 'Updated og_image',
                'og_url' => 'Updated og_url',
                'og_type' => 'Updated og_type',
                'canonical_url' => 'Updated canonical_url',
                'robots' => 'Updated robots'
            ],
            expectedModel: $content
        );
    }

    public function testDeleteContent(): void
    {
        $menu = Menu::factory()->create();

        $content = new Content([
            'id' => 1,
            'menu_id' => $menu->id,
            'content' => 'Some content',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_url' => 'og_url',
            'og_type' => 'og_type',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots'
        ]);

        $this->assertDeleteEntity(
            model: $content
        );
    }

    public function testListContent(): void
    {
        $menus = Menu::factory()->count(2)->create();

        $contents = [
            tap(new Content([
                'id' => 1,
                'menu_id' => $menus[0]->id,
                'content' => 'First content',
                'title' => 'First title',
                'meta_description' => 'First meta_description',
                'meta_keywords' => 'First meta_keywords',
                'og_title' =>'First og_title',
                'og_description' => 'First og_description',
                'og_image' => 'First og_image',
                'og_url' => 'First og_url',
                'og_type' => 'First og_type',
                'canonical_url' => 'First canonical_url',
                'robots' => 'First robots'
            ]), fn($c) => $c->exists = true),
            tap(new Content([
                'id' => 2,
                'menu_id' => $menus[1]->id,
                'content' => 'Second content',
                'title' => 'Second title',
                'meta_description' => 'Second meta_description',
                'meta_keywords' => 'Second meta_keywords',
                'og_title' =>'Second og_title',
                'og_description' => 'Second og_description',
                'og_image' => 'Second og_image',
                'og_url' => 'Second og_url',
                'og_type' => 'Second og_type',
                'canonical_url' => 'Second canonical_url',
                'robots' => 'Second robots'
            ]), fn($c) => $c->exists = true),
        ];

        $collection = new \Illuminate\Database\Eloquent\Collection($contents);

        $this->repository
            ->expects($this->once())
            ->method('all')
            ->willReturn($collection);

        $result = $this->service->all(false); // без пагинации

        $this->assertCount(2, $result);
        $this->assertEquals('First content', $result[0]->content);
        $this->assertEquals('Second content', $result[1]->content);
        $this->assertEquals('First title', $result[0]->title);
        $this->assertEquals('Second title', $result[1]->title);
        $this->assertEquals('First meta_description', $result[0]->meta_description);
        $this->assertEquals('Second meta_description', $result[1]->meta_description);
        $this->assertEquals('First meta_keywords', $result[0]->meta_keywords);
        $this->assertEquals('Second meta_keywords', $result[1]->meta_keywords);
        $this->assertEquals('First meta_keywords', $result[0]->meta_keywords);
        $this->assertEquals('Second meta_keywords', $result[1]->meta_keywords);
        $this->assertEquals('First og_description', $result[0]->og_description);
        $this->assertEquals('Second og_description', $result[1]->og_description);
        $this->assertEquals('First og_image', $result[0]->og_image);
        $this->assertEquals('Second og_image', $result[1]->og_image);
        $this->assertEquals('First og_url', $result[0]->og_url);
        $this->assertEquals('Second og_url', $result[1]->og_url);
        $this->assertEquals('First og_type', $result[0]->og_type);
        $this->assertEquals('Second og_type', $result[1]->og_type);
        $this->assertEquals('First canonical_url', $result[0]->canonical_url);
        $this->assertEquals('Second canonical_url', $result[1]->canonical_url);
        $this->assertEquals('First robots', $result[0]->robots);
        $this->assertEquals('Second robots', $result[1]->robots);
    }

    public function testShowContent(): void
    {
        $menu = new Menu(['id' => 1]);
        $content = new Content([
            'id' => 1,
            'menu_id' => $menu->id,
            'content' => 'Some content',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_url' => 'og_url',
            'og_type' => 'og_type',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots'
        ]);
        $content->exists = true;

        // Настройка мока
        $this->repository
            ->method('find')
            ->willReturn($content);

        /** @var Content $result */
        $result = $this->service->find(1);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals($menu->id, $result->menu_id);
        $this->assertEquals('Some content', $result->content);
        $this->assertEquals('title', $result->title);
        $this->assertEquals('meta_description', $result->meta_description);
        $this->assertEquals('meta_keywords', $result->meta_keywords);
        $this->assertEquals('og_title', $result->og_title);
        $this->assertEquals('og_description', $result->og_description);
        $this->assertEquals('og_image', $result->og_image);
        $this->assertEquals('og_url', $result->og_url);
        $this->assertEquals('og_type', $result->og_type);
        $this->assertEquals('canonical_url', $result->canonical_url);
        $this->assertEquals('robots', $result->robots);
    }
}
