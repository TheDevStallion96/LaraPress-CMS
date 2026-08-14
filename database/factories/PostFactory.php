<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => fake()->sentence(),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']),
            'excerpt' => fake()->paragraph(),
            'content' => [],
            'type' => 'post',
            'status' => 'draft',
            'comment_status' => 'open',
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the post is a published post.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the post is scheduled for the future.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'future',
            'published_at' => now()->addDay(),
        ]);
    }

    /**
     * Indicate that the post is a page.
     */
    public function asPage(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'page',
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
