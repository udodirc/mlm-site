<?php

namespace Tests\Unit;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Models\Content;
use App\Models\Menu;
use App\Repositories\ContentRepository;
use App\Services\ContentService;
use Illuminate\Database\Eloquent\Collection;

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
            'Some content'
        );

        $content = new Content([
            'menu_id' => 1,
            'content' => 'Some content'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'menu_id' => 1,
                'content' => 'Some content'
            ],
            expectedModel: $content
        );
    }

    public function testUpdateContent(): void
    {
        $menu = Menu::factory()->create();

        $dto = new ContentUpdateData(
            $menu->id,
            'Updated content'
        );

        $content = new Content([
            'menu_id' => $menu->id,
            'content' => 'Some content'
        ]);

        $content->menu_id = $menu->id;
        $content->content = 'Updated content';

        $this->assertUpdateEntity(
            model: $content,
            updateDto: $dto,
            expectedInput: [
                'menu_id' => $menu->id,
                'content' => 'Updated content'  // ожидаемые данные после обновления
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
            'content' => 'Some content'
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
            ]), fn($c) => $c->exists = true),
            tap(new Content([
                'id' => 2,
                'menu_id' => $menus[1]->id,
                'content' => 'Second content',
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
    }

    public function testShowContent(): void
    {
        $menu = new Menu(['id' => 1]);
        $content = new Content([
            'id' => 1,
            'menu_id' => $menu->id,
            'content' => 'Some content',
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
    }
}
