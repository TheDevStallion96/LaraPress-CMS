<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the default WordPress roles.
     */
    public function run(): void
    {
        $all = array_keys(config('capabilities.capabilities'));

        foreach (config('capabilities.roles') as $slug => $capabilities) {
            Role::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => ucfirst($slug),
                    // The administrator implicitly holds every capability.
                    'capabilities' => $slug === 'administrator' ? $all : $capabilities,
                ],
            );
        }
    }
}
