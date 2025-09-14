<?php

namespace Feature;

use App\Enums\PermissionsEnum;
use Illuminate\Http\UploadedFile;
use Tests\Feature\BaseTest;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProjectFilesUploadJob;
use App\Models\Project;

class ProjectTest extends BaseTest
{
    public function testProjectContentWithImages(): void
    {
        $this->auth(PermissionsEnum::ProjectCreate->value);

        Queue::fake();

        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ];

        $data = [
            'name' => 'Some name',
            'content' => 'Some content',
            'url' => 'url',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_type' => 'og_type',
            'og_url' => 'og_url',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots',
            'images' => $files,
            'main_page' => 'img0' // например, первая картинка главная
        ];

        $response = $this->postJson(route('project.store'), $data);

        $response->assertCreated();

        Queue::assertPushed(ProjectFilesUploadJob::class);

        $this->assertDatabaseHas('projects', [
            'name' => 'Some name',
            'content' => 'Some content',
            'url' => 'url',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_type' => 'og_type',
            'og_url' => 'og_url',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots',
        ]);
    }

    public function testProjectUpdateWithImages(): void
    {
        $this->auth(PermissionsEnum::ProjectUpdate->value);

        Queue::fake();

        $project = Project::factory()->create([
            'name' => 'Old name',
            'content' => 'Old content',
            'url' => 'old-url',
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
            'main_page' => 'img0',
        ]);

        $files = [
            UploadedFile::fake()->image('updated_image1.jpg'),
            UploadedFile::fake()->image('updated_image2.jpg'),
        ];

        $data = [
            'name' => 'Updated name',
            'content' => 'Updated content',
            'url' => 'updated-url',
            'status' => false,
            'title' => 'Updated title',
            'meta_description' => 'Updated meta_description',
            'meta_keywords' => 'Updated meta_keywords',
            'og_title' => 'Updated og_title',
            'og_description' => 'Updated og_description',
            'og_image' => 'Updated og_image',
            'og_type' => 'Updated og_type',
            'og_url' => 'Updated og_url',
            'canonical_url' => 'Updated canonical_url',
            'robots' => 'Updated robots',
            'images' => $files,
            'main_page' => 'img1',
        ];

        $response = $this->postJson(
            route('project.update', ['project' => $project->id]),
            $data
        );

        $response->assertOk();
        Queue::assertPushed(ProjectFilesUploadJob::class);

        $dataForDb = collect($data)->except(['images', 'main_page'])->toArray();

        $dataForDb['id'] = $project->id;

        $this->assertDatabaseHas('projects', $dataForDb);
    }


    public function testDeleteProject(): void
    {
        $this->auth(PermissionsEnum::ProjectDelete->value);

        $project = Project::factory()->create();

        $this->performAction(
            action: 'delete',
            route: 'project.destroy',
            id: $project->id,
            table: 'projects'
        );
    }

    public function testProjectsList(): void
    {
        $this->auth(PermissionsEnum::ProjectView->value);

        Project::factory()->count(3)->create();

        $this->performAction(
            action: 'list',
            route: 'project.index',
            expectedCount: 3
        );
    }

    public function testSingleContentWithFiles(): void
    {
        $this->auth(PermissionsEnum::ProjectView->value);

        Queue::fake();

        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ];

        $data = [
            'name' => 'Project with files',
            'content' => 'Content here',
            'url' => 'project-url',
            'title' => 'title',
            'meta_description' => 'meta_description',
            'meta_keywords' => 'meta_keywords',
            'og_title' =>'og_title',
            'og_description' => 'og_description',
            'og_image' => 'og_image',
            'og_type' => 'og_type',
            'og_url' => 'og_url',
            'canonical_url' => 'canonical_url',
            'robots' => 'robots',
            'images' => $files,
            'main_page' => 'img0'
        ];

        // создаём проект через API
        $this->postJson(route('project.store'), $data)
            ->assertCreated();

        Queue::assertPushed(ProjectFilesUploadJob::class);

        // теперь GET запрс на show
        $project = Project::first();

        $this->getJson(route('project.show', ['project' => $project->id]))
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Project with files',
                'content' => 'Content here',
                'url' => 'project-url',
                'title' => 'title',
                'meta_description' => 'meta_description',
                'meta_keywords' => 'meta_keywords',
                'og_title' =>'og_title',
                'og_description' => 'og_description',
                'og_image' => 'og_image',
                'og_type' => 'og_type',
                'og_url' => 'og_url',
                'canonical_url' => 'canonical_url',
                'robots' => 'robots',
            ]);
    }
}
