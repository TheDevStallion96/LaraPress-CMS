<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Post;
use App\Models\Role;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    /**
     * Seed sample content so the admin panel and public site have data.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        );

        $admin->roles()->syncWithoutDetaching(
            Role::where('slug', 'administrator')->pluck('id'),
        );

        $uncategorized = Term::where('slug', 'uncategorized')->first();
        $news = Term::where('slug', 'news')->first();
        $laravel = Term::where('slug', 'laravel')->first();

        $posts = [
            'hello-larapress' => [
                'title' => 'Hello, LaraPress',
                'excerpt' => 'Welcome to your new content management system.',
                'status' => 'published',
                'terms' => [$uncategorized, $laravel],
            ],
            'building-a-cms' => [
                'title' => 'Building a WordPress Clone with Laravel',
                'excerpt' => 'A look at the architecture behind LaraPress.',
                'status' => 'published',
                'terms' => [$news, $laravel],
            ],
            'a-draft-post' => [
                'title' => 'A Draft Post',
                'excerpt' => 'This post is still being written.',
                'status' => 'draft',
                'terms' => [],
            ],
        ];

        $published = collect();

        foreach ($posts as $slug => $data) {
            $post = Post::query()->updateOrCreate(
                ['type' => 'post', 'slug' => $slug],
                [
                    'author_id' => $admin->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'status' => $data['status'],
                    'content' => [
                        ['id' => Str::uuid()->toString(), 'type' => 'paragraph', 'props' => ['content' => $data['excerpt']]],
                    ],
                    'published_at' => $data['status'] === 'published' ? now() : null,
                ],
            );

            if (! empty($data['terms'])) {
                $post->terms()->sync(array_filter(
                    array_map(fn (?Term $term) => $term?->id, $data['terms']),
                ));
            }

            if ($data['status'] === 'published') {
                $published->push($post);
            }
        }

        Post::query()->updateOrCreate(
            ['type' => 'page', 'slug' => 'about'],
            [
                'author_id' => $admin->id,
                'title' => 'About',
                'excerpt' => 'Learn more about this site.',
                'status' => 'published',
                'content' => [
                    ['id' => Str::uuid()->toString(), 'type' => 'paragraph', 'props' => ['content' => 'This page was created during installation.']],
                ],
                'published_at' => now(),
            ],
        );

        $menu = Menu::query()->firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Primary'],
        );

        $homeItem = MenuItem::query()->firstOrCreate(
            ['menu_id' => $menu->id, 'label' => 'Home'],
            ['type' => 'custom', 'url' => '/', 'order' => 0],
        );

        MenuItem::query()->firstOrCreate(
            ['menu_id' => $menu->id, 'label' => 'About'],
            ['type' => 'page', 'url' => '/about', 'order' => 1],
        );

        $firstPublished = $published->first();

        if ($firstPublished !== null) {
            Comment::query()->create([
                'post_id' => $firstPublished->id,
                'author_name' => 'A Visitor',
                'author_email' => 'visitor@example.com',
                'content' => 'Congratulations on your new site!',
                'status' => 'approved',
            ]);
        }
    }
}
