<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'content' => $this->faker->paragraphs(3, true),
            'title' => fake()->words(2, true),
            'meta_description' => fake()->words(2, true),
            'meta_keywords' => fake()->words(2, true),
            'og_title' => fake()->words(2, true),
            'og_description' => fake()->words(2, true),
            'og_image' => fake()->words(2, true),
            'og_type' => fake()->words(2, true),
            'og_url' => fake()->words(2, true),
            'canonical_url' => fake()->words(2, true),
            'robots' => fake()->words(2, true),
        ];
    }
}
