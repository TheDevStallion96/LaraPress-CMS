<?php

namespace Database\Factories;

use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Term>
 */
class TermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'taxonomy_id' => Taxonomy::factory(),
            'name' => fake()->unique()->word(),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']),
            'description' => fake()->sentence(),
            'count' => 0,
        ];
    }
}
