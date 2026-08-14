<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('profile settings render under the admin prefix', function () {
    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('settings/Profile'));
});

test('appearance settings render under the admin prefix', function () {
    $this->get(route('appearance.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('settings/Appearance'));
});

test('the legacy settings path redirects to the admin settings', function () {
    $this->get('/settings')->assertRedirect('/admin/settings/profile');
});
