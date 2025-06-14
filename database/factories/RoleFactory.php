<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class RoleFactory extends Factory
{
    // Указываем нужную модель
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle, // уникальные имена ролей
            'guard_name' => 'api',
        ];
    }
}
