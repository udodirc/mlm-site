<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'name'   => $this->faker->unique()->word(),
            'key'   => $this->faker->unique()->word(),
            'value' => $this->faker->sentence(),
        ];
    }
}
