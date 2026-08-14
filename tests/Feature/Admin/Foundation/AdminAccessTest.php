<?php

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SampleContentSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

function adminUser(): User
{
    $role = Role::where('slug', 'administrator')->firstOrFail();
    $user = User::factory()->create();
    $user->roles()->attach($role);

    return $user;
}

test('guests are redirected to login for admin routes', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('any authenticated user with read can access the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Dashboard'));
});

test('unverified users can still access the admin dashboard without a redirect loop', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

test('administrator can access the admin dashboard', function () {
    $admin = adminUser();

    $this->actingAs($admin)->get(route('dashboard'))->assertOk();
});

test('dashboard renders quick stats', function () {
    $admin = adminUser();

    Post::factory()->count(3)->published()->create(['author_id' => $admin->id]);
    Post::factory()->create(['author_id' => $admin->id]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->where('stats.posts.total', 4)
            ->where('stats.posts.published', 3)
            ->where('stats.users', 1));
});

test('public home page renders for guests', function () {
    $this->seed(SampleContentSeeder::class);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Public/Home'));
});
