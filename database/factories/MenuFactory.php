<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true), // 'Main Menu', 'Top Bar' и т.д.
            'parent_id' => null,              // по умолчанию корневое
        ];
    }

    /**
     * Состояние для дочернего меню
     */
    public function withParent(?Menu $parent = null): static
    {
        return $this->state(function () use ($parent) {
            return [
                'parent_id' => $parent?->id ?? Menu::factory(),
            ];
        });
    }
}
