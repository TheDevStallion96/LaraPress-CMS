<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => fake()->word().'.png',
            'path' => 'uploads/'.Str::uuid().'.png',
            'disk' => 'public',
            'mime_type' => 'image/png',
            'width' => 1200,
            'height' => 800,
            'size' => fake()->numberBetween(1000, 5_000_000),
            'alt' => fake()->sentence(),
            'conversions' => [],
        ];
    }
}
