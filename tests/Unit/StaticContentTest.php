<?php

namespace Tests\Unit;

use App\Data\Admin\StaticContent\StaticContentCreateData;
use App\Data\Admin\StaticContent\StaticContentUpdateData;
use App\Models\StaticContent;
use App\Repositories\StaticContentRepository;
use App\Services\StaticContentService;
use Illuminate\Database\Eloquent\Collection;

class StaticContentTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return StaticContentService::class;
    }

    protected function getRepositoryClass(): string
    {
        return StaticContentRepository::class;
    }

    public function testCreateContent(): void
    {
        $dto = new StaticContentCreateData(
            name: 'Test',
            content: 'Some content',
        );

        $content = new StaticContent([
            'name' => 'Test',
            'content' => 'Some content'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'name' => 'Test',
                'content' => 'Some content'
            ],
            expectedModel: $content
        );
    }

    public function testUpdateContent(): void
    {
        $dto = new StaticContentUpdateData(
            'Updated test',
            'Updated content'
        );

        $content = new StaticContent([
            'name' => 'Test',
            'content' => 'Some content'
        ]);

        $content->name = 'Updated test';
        $content->content = 'Updated content';

        $this->assertUpdateEntity(
            model: $content,
            updateDto: $dto,
            expectedInput: [
                'name' => 'Updated test',
                'content' => 'Updated content'
            ],
            expectedModel: $content
        );
    }

    public function testDeleteContent(): void
    {
        $content = new StaticContent([
            'id' => 1,
            'name' => 'Test',
            'content' => 'Some content'
        ]);

        $this->assertDeleteEntity(
            model: $content
        );
    }

    public function testListContent(): void
    {
        $contents = [
            tap(new StaticContent([
                'id' => 1,
                'name' => 'First test',
                'content' => 'First content',
            ]), fn($c) => $c->exists = true),
            tap(new StaticContent([
                'id' => 2,
                'name' => 'Second test',
                'content' => 'Second content',
            ]), fn($c) => $c->exists = true),
        ];

        $collection = new Collection($contents);

        $this->repository
            ->expects($this->once())
            ->method('all')
            ->willReturn($collection);

        $result = $this->service->all(false);

        $this->assertCount(2, $result);
        $this->assertEquals('First content', $result[0]->content);
        $this->assertEquals('Second content', $result[1]->content);
    }

    public function testShowContent(): void
    {
        $content = new StaticContent([
            'id' => 1,
            'name' => 'Test',
            'content' => 'Some content',
        ]);
        $content->exists = true;

        // Настройка мока
        $this->repository
            ->method('find')
            ->willReturn($content);

        /** @var StaticContent $result */
        $result = $this->service->find(1);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test', $result->name);
        $this->assertEquals('Some content', $result->content);
    }
}
