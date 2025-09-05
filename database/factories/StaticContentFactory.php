<?php

namespace Database\Factories;

use App\Models\StaticContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class StaticContentFactory extends Factory
{
    protected $model = StaticContent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'content' => $this->faker->paragraphs(3, true)
        ];
    }
}
