<?php

namespace Database\Seeders;

use App\Enums\PaginationEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            PaginationEnum::User->value => 'Количество пользователей на странице',
            PaginationEnum::Role->value => 'Количество ролей на странице',
            PaginationEnum::Menu->value => 'Количество меню на странице',
            PaginationEnum::Content->value => 'Количество контента на странице',
            'admin_email' => 'admin@example.com',
        ];

        foreach ($settings as $key => $name) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['name' => $name, 'value' => config('app.default_pagination')]
            );
        }
    }
}
