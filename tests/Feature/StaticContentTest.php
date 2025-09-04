<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use Database\Factories\StaticContentFactory;
use Illuminate\Http\Response;
use App\Models\StaticContent;

class StaticContentTest extends BaseTest
{
    public function testCreateStaticContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentCreate->value);

        $data = [
            'name' => 'Test',
            'content' => 'Some content',
        ];

        $response = $this->postJson(route('static_content.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('static_content', ['name' => 'Test', 'content' => 'Some content']);
        $response->assertJsonFragment(['name' => 'Test', 'content' => 'Some content']);
    }

    public function testUpdateStaticContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentUpdate->value);

        $content = StaticContent::factory()->create();

        $data = ['name' => 'Updated name','content' => 'Updated content', 'status' => false];

        $response = $this->putJson(route('static_content.update', $content->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'Updated name']);
        $response->assertJsonFragment(['content' => 'Updated content']);
        $response->assertJsonFragment(['status' => false]);
    }

    public function testDeleteContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentDelete->value);

        $content = StaticContent::factory()->create();

        $response = $this->deleteJson(route('static_content.destroy', $content->id));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('static_content', ['id' => $content->id]);
    }

    public function testContentsList(): void
    {
        $this->auth(PermissionsEnum::StaticContentView->value);

        StaticContent::factory()->count(3)->create();

        $response = $this->getJson(route('static_content.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
    }

    public function testSingleContent(): void
    {
        $this->auth(PermissionsEnum::StaticContentView->value);

        $content = StaticContent::factory()->create();

        $response = $this->getJson(route('static_content.show', $content->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['content' => $content->content]);
    }
}
