<?php

namespace Tests\Unit;

use App\Data\Admin\Content\ContentCreateData;
use App\Data\Admin\Content\ContentUpdateData;
use App\Data\Admin\Project\ProjectCreateData;
use App\Data\Admin\Project\ProjectUpdateData;
use App\Models\Content;
use App\Models\Menu;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Services\ProjectService;
use Illuminate\Http\UploadedFile;

class ProjectTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return ProjectService::class;
    }

    protected function getRepositoryClass(): string
    {
        return ProjectRepository::class;
    }

    public function testCreateProjectWithoutFiles(): void
    {
        // DTO без файлов
        $dto = new ProjectCreateData(
            name: 'Project without files',
            content: 'Some content',
            url: 'project-url',
            title: 'Title',
            meta_description: 'Meta description',
            meta_keywords: 'Meta keywords',
            og_title: 'OG title',
            og_description: 'OG description',
            og_image: 'OG image',
            og_url: 'OG url',
            og_type: 'website',
            canonical_url: 'Canonical URL',
            robots: 'index, follow',
            images: [],       // пустой массив
            main_page: null   // без главного изображения
        );

        // Ожидаемый массив для репозитория
        $expectedInput = [
            'name' => 'Project without files',
            'content' => 'Some content',
            'url' => 'project-url',
            'title' => 'Title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords',
            'og_title' => 'OG title',
            'og_description' => 'OG description',
            'og_image' => 'OG image',
            'og_type' => 'website',
            'og_url' => 'OG url',
            'canonical_url' => 'Canonical URL',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ];

        // Ожидаемая модель, которую вернёт репозиторий
        $expectedModel = new Project($expectedInput);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: $expectedInput,
            expectedModel: $expectedModel
        );
    }



    public function testUpdateProjectWithoutFiles(): void
    {
        // Существующий проект
        $project = new Project([
            'name' => 'Old Project',
            'content' => 'Old content',
            'url' => 'old-url',
            'status' => true,
            'title' => 'Old title',
            'meta_description' => 'Old meta_description',
            'meta_keywords' => 'Old meta_keywords',
            'og_title' => 'Old og_title',
            'og_description' => 'Old og_description',
            'og_image' => 'Old og_image',
            'og_url' => 'Old og_url',
            'og_type' => 'website',
            'canonical_url' => 'Old canonical_url',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ]);

        // DTO для обновления
        $dto = new ProjectUpdateData(
            name: 'Updated Project',
            content: 'Updated content',
            url: 'updated-url',
            status: false,
            title: 'Updated title',
            meta_description: 'Updated meta_description',
            meta_keywords: 'Updated meta_keywords',
            og_title: 'Updated og_title',
            og_description: 'Updated og_description',
            og_image: 'Updated og_image',
            og_url: 'Updated og_url',
            og_type: 'website',
            canonical_url: 'Updated canonical_url',
            robots: 'index, follow',
            images: [],       // без файлов
            main_page: null
        );

        // Ожидаемые данные для репозитория
        $expectedInput = [
            'name' => 'Updated Project',
            'content' => 'Updated content',
            'url' => 'updated-url',
            'status' => false,
            'title' => 'Updated title',
            'meta_description' => 'Updated meta_description',
            'meta_keywords' => 'Updated meta_keywords',
            'og_title' => 'Updated og_title',
            'og_description' => 'Updated og_description',
            'og_image' => 'Updated og_image',
            'og_url' => 'Updated og_url',
            'og_type' => 'website',
            'canonical_url' => 'Updated canonical_url',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ];

        // Модель, которую вернёт репозиторий
        $expectedModel = clone $project;
        foreach ($expectedInput as $key => $value) {
            $expectedModel->$key = $value;
        }

        $this->assertUpdateEntity(
            model: $project,
            updateDto: $dto,
            expectedInput: $expectedInput,
            expectedModel: $expectedModel
        );
    }

    public function testDeleteProject(): void
    {
        $project = new Project([
            'name' => 'Old Project',
            'content' => 'Old content',
            'url' => 'old-url',
            'title' => 'Old title',
            'meta_description' => 'Old meta_description',
            'meta_keywords' => 'Old meta_keywords',
            'og_title' => 'Old og_title',
            'og_description' => 'Old og_description',
            'og_image' => 'Old og_image',
            'og_url' => 'Old og_url',
            'og_type' => 'website',
            'canonical_url' => 'Old canonical_url',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ]);

        $this->assertDeleteEntity(
            model: $project
        );
    }

    public function testListProject(): void
    {
        $contents = [
            tap(new Project([
                'id' => 1,
                'name' => 'First name',
                'content' => 'First content',
                'url' => 'first-url',
                'title' => 'First title',
                'meta_description' => 'First meta_description',
                'meta_keywords' => 'First meta_keywords',
                'og_title' =>'First og_title',
                'og_description' => 'First og_description',
                'og_image' => 'First og_image',
                'og_url' => 'First og_url',
                'og_type' => 'First og_type',
                'canonical_url' => 'First canonical_url',
                'robots' => 'First robots',
                'images' => [],
                'main_page' => null,
            ]), fn($c) => $c->exists = true),
            tap(new Project([
                'id' => 2,
                'name' => 'Second name',
                'content' => 'Second content',
                'url' => 'second-url',
                'title' => 'Second title',
                'meta_description' => 'Second meta_description',
                'meta_keywords' => 'Second meta_keywords',
                'og_title' =>'Second og_title',
                'og_description' => 'Second og_description',
                'og_image' => 'Second og_image',
                'og_url' => 'Second og_url',
                'og_type' => 'Second og_type',
                'canonical_url' => 'Second canonical_url',
                'robots' => 'Second robots',
                'images' => [],
                'main_page' => null,
            ]), fn($c) => $c->exists = true),
        ];

        $collection = new \Illuminate\Database\Eloquent\Collection($contents);

        $this->repository
            ->expects($this->once())
            ->method('all')
            ->willReturn($collection);

        $result = $this->service->all(false); // без пагинации

        $this->assertCount(2, $result);
        $this->assertEquals('First name', $result[0]->name);
        $this->assertEquals('Second name', $result[1]->name);
        $this->assertEquals('First content', $result[0]->content);
        $this->assertEquals('Second content', $result[1]->content);
        $this->assertEquals('First title', $result[0]->title);
        $this->assertEquals('Second title', $result[1]->title);
        $this->assertEquals('first-url', $result[0]->url);
        $this->assertEquals('second-url', $result[1]->url);
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

    public function testShowProject(): void
    {
        $project = new Project([
            'id' => 1,
            'name' => 'Project without files',
            'content' => 'Some content',
            'url' => 'project-url',
            'title' => 'Title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords',
            'og_title' => 'OG title',
            'og_description' => 'OG description',
            'og_image' => 'OG image',
            'og_type' => 'website',
            'og_url' => 'OG url',
            'canonical_url' => 'Canonical URL',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ]);
        $project->exists = true;

        $this->repository
            ->method('find')
            ->willReturn($project);

        /** @var Project $result */
        $result = $this->service->find(1);

        $this->assertNotNull($result);

        $expectedAttributes = [
            'id' => 1,
            'name' => 'Project without files',
            'content' => 'Some content',
            'url' => 'project-url',
            'title' => 'Title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords',
            'og_title' => 'OG title',
            'og_description' => 'OG description',
            'og_image' => 'OG image',
            'og_type' => 'website',
            'og_url' => 'OG url',
            'canonical_url' => 'Canonical URL',
            'robots' => 'index, follow',
            'images' => [],
            'main_page' => null,
        ];

        foreach ($expectedAttributes as $key => $value) {
            $actual = $result->$key;

            if ($key === 'images' && $actual === null) {
                $actual = [];
            }

            $this->assertEquals($value, $actual, "Attribute {$key} does not match");
        }
    }
}
