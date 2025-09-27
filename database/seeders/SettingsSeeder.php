<?php
namespace Database\Seeders;

use App\Enums\PaginationEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PaginationEnum::cases() as $case) {
            Setting::updateOrCreate(
                ['key' => $case->value],
                ['name' => $case->label(), 'value' => config('app.default_pagination')]
            );
        }

        // другие настройки, которые не связаны с enum
        Setting::updateOrCreate(
            ['key' => 'admin_email'],
            ['name' => 'Email администратора', 'value' => 'admin@example.com']
        );
    }
}
