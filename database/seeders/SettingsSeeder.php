<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the default site settings.
     */
    public function run(): void
    {
        $settings = [
            'site_title' => config('app.name'),
            'tagline' => 'Just another LaraPress site',
            'site_url' => url('/'),
            'posts_per_page' => '10',
            'default_category' => null,
            'comments_allow' => '1',
            'comments_moderation' => '1',
            'front_page' => 'latest-posts',
            'front_page_id' => null,
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
