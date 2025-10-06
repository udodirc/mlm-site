<?php

namespace Database\Seeders;

use App\Enums\PaginationEnum;
use App\Enums\SettingsEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Настройки пагинации
        foreach (PaginationEnum::cases() as $case) {
            Setting::updateOrCreate(
                ['key' => $case->value],
                [
                    'name' => $case->label(),
                    'value' => config('app.default_pagination'),
                ]
            );
        }

        // Все остальные системные настройки
        foreach (SettingsEnum::cases() as $case) {
            Setting::updateOrCreate(
                ['key' => $case->value],
                [
                    'name' => $case->label(),
                    'value' => $case->defaultValue(),
                ]
            );
        }
    }
}
