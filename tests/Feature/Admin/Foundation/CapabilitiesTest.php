<?php

use App\Models\Role;
use App\Models\User;
use App\Services\CapabilityService;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

function roleUser(string $slug): User
{
    $role = Role::where('slug', $slug)->firstOrFail();
    $user = User::factory()->create();
    $user->roles()->attach($role);

    return $user;
}

test('administrator holds every capability', function () {
    $admin = roleUser('administrator');

    $capabilities = app(CapabilityService::class);

    expect($capabilities->allFor($admin))
        ->toContain('read')
        ->toContain('edit_posts')
        ->toContain('manage_options');

    expect($capabilities->can('publish_posts', $admin))->toBeTrue();
    expect($capabilities->can('manage_options', $admin))->toBeTrue();
});

test('editor can manage content but not users or options', function () {
    $editor = roleUser('editor');

    $capabilities = app(CapabilityService::class);

    expect($capabilities->can('edit_posts', $editor))->toBeTrue();
    expect($capabilities->can('publish_posts', $editor))->toBeTrue();
    expect($capabilities->can('moderate_comments', $editor))->toBeTrue();

    expect($capabilities->can('manage_options', $editor))->toBeFalse();
    expect($capabilities->can('delete_users', $editor))->toBeFalse();
});

test('author can publish their own posts but not edit users', function () {
    $author = roleUser('author');

    $capabilities = app(CapabilityService::class);

    expect($capabilities->can('publish_posts', $author))->toBeTrue();
    expect($capabilities->can('edit_posts', $author))->toBeTrue();
    expect($capabilities->can('edit_others_posts', $author))->toBeFalse();
    expect($capabilities->can('edit_users', $author))->toBeFalse();
});

test('contributor can edit but not publish posts', function () {
    $contributor = roleUser('contributor');

    $capabilities = app(CapabilityService::class);

    expect($capabilities->can('edit_posts', $contributor))->toBeTrue();
    expect($capabilities->can('publish_posts', $contributor))->toBeFalse();
});

test('subscriber can only read', function () {
    $subscriber = roleUser('subscriber');

    $capabilities = app(CapabilityService::class);

    expect($capabilities->allFor($subscriber))->toBe(['read']);
    expect($capabilities->can('read', $subscriber))->toBeTrue();
    expect($capabilities->can('edit_posts', $subscriber))->toBeFalse();
});

test('users without roles fall back to the read capability', function () {
    $user = User::factory()->create();

    $capabilities = app(CapabilityService::class);

    expect($capabilities->allFor($user))->toContain('read');
    expect($capabilities->can('read', $user))->toBeTrue();
});

test('the gate grants every capability to administrators', function () {
    $admin = roleUser('administrator');

    $this->actingAs($admin);

    expect(Gate::allows('manage_options'))->toBeTrue();
    expect(Gate::allows('some_unknown_capability'))->toBeTrue();
});

test('the gate resolves capabilities for non-admin users', function () {
    $author = roleUser('author');

    $this->actingAs($author);

    expect(Gate::allows('publish_posts'))->toBeTrue();
    expect(Gate::allows('edit_others_posts'))->toBeFalse();
});
