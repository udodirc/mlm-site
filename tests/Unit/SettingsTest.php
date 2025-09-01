<?php

namespace Tests\Unit;

use App\Data\Admin\Setting\SettingCreateData;
use App\Data\Admin\Setting\SettingUpdateData;
use App\Models\Setting;
use App\Repositories\SettingRepository;
use App\Services\SettingService;
use Illuminate\Database\Eloquent\Collection;

class SettingsTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return SettingService::class;
    }

    protected function getRepositoryClass(): string
    {
        return  SettingRepository::class;
    }

    public function testCreateSettings(): void
    {
        $dto = new  SettingCreateData(
            'Количество пользователей на странице',
            key: 'per_page_users',
            value: '10'
        );

        $settings = new Setting([
            'Количество пользователей на странице',
            'key' => 'per_page_users',
            'value' => '10'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'key' => 'per_page_users',
                'value' => '10'
            ],
            expectedModel: $settings
        );
    }

    public function testUpdateSettings(): void
    {
        $dto = new SettingUpdateData(
            'Количество ролей на странице',
            key: 'per_page_roles',
            value: '5'
        );

        $settings = new Setting([
            'Количество пользователей на странице',
            'key' => 'per_page_users',
            'value' => '10'
        ]);

        $settings->name = 'Количество ролей на странице';
        $settings->key = 'per_page_roles';
        $settings->value = '5';

        $this->assertUpdateEntity(
            model: $settings,
            updateDto: $dto,
            expectedInput: [
                'name' => 'Количество ролей на странице',
                'key' => 'per_page_roles',
                'value' => '5'
            ],
            expectedModel: $settings
        );
    }

    public function testDeleteSettings(): void
    {
        $settings = new Setting([
            'id' => 1,
            'name' => 'Количество пользователей на странице',
            'key' => 'per_page_users',
            'value' => '10'
        ]);

        $this->assertDeleteEntity(
            model: $settings
        );
    }

    public function testListSettings(): void
    {
        $settings = new Collection([
            new Setting([
                'id' => 1,
                'name' => 'Количество пользователей на странице',
                'key' => 'per_page_users',
                'value' => '10'
            ]),
            new Setting([
                'id' => 2,
                'name' => 'Количество ролей на странице',
                'key' => 'per_page_roles',
                'value' => '5'
            ]),
        ]);

        $this->assertListItemsEntity(
            model: $settings,
            items: ['per_page_users', 'per_page_roles'],
            field: 'key'
        );
    }

    public function testShowSetting(): void
    {
        $setting = new Setting([
            'id' => 1,
            'name' => 'Количество пользователей на странице',
            'key' => 'per_page_users',
            'value' => '10',
        ]);
        $setting->exists = true;

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($setting);

        /** @var Setting $result */
        $result = $this->service->find(1);

        $this->assertEquals(1, $result->id);
        $this->assertEquals('Количество пользователей на странице', $result->name);
        $this->assertEquals('per_page_users', $result->key);
        $this->assertEquals('10', $result->value);
    }
}
