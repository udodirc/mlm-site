<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Models\Setting;
use Illuminate\Http\Response;

class SettingsTest extends BaseTest
{
    public function testCreateSettings(): void
    {
        $this->auth(PermissionsEnum::SettingsCreate->value);

        $data = [
            'name' => 'test_name',
            'key' => 'test_key',
            'value' => 'test_value'
        ];

        $response = $this->postJson(route('settings.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => 'test_name']);
        $response->assertJsonFragment(['key' => 'test_key']);
        $response->assertJsonFragment(['value' => 'test_value']);
    }

    public function testUpdateSettings(): void
    {
        $this->auth(PermissionsEnum::SettingsUpdate->value);
        $settings = Setting::factory()->create();

        $data = [
            'name' => 'Updated test_name',
            'key' => 'Updated test_key',
            'value' => 'Updated test_value'
        ];

        $response = $this->putJson(route('settings.update', $settings->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'Updated test_name']);
        $response->assertJsonFragment(['key' => 'Updated test_key']);
        $response->assertJsonFragment(['value' => 'Updated test_value']);
    }

    public function testDeleteSettings(): void
    {
        $this->auth(PermissionsEnum::SettingsDelete->value);

        $settings = Setting::factory()->create();

        $response = $this->deleteJson(route('settings.destroy', $settings));

        $response->assertStatus(200);
    }

    public function testSettingsList(): void
    {
        $this->auth(PermissionsEnum::SettingsView->value);

        Setting::factory()->count(3)->create();

        $response = $this->getJson(route('settings.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
    }

    public function testSingleSetting(): void
    {
        $this->auth(PermissionsEnum::SettingsView->value);

        $settings = Setting::factory()->create();

        $response = $this->getJson(route('settings.show', $settings->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $settings->name]);
        $response->assertJsonFragment(['key' => $settings->key]);
        $response->assertJsonFragment(['value' => $settings->value]);
    }
}
