<?php

use Illuminate\Support\Facades\Schema;

test('foundation tables exist', function () {
    $tables = [
        'roles',
        'role_user',
        'taxonomies',
        'terms',
        'media',
        'posts',
        'post_meta',
        'revisions',
        'post_term',
        'comments',
        'menus',
        'menu_items',
        'settings',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue("Missing table: {$table}");
    }
});

test('users table has profile fields', function () {
    expect(Schema::hasColumns('users', ['display_name', 'first_name', 'last_name', 'bio']))->toBeTrue();
});

test('posts table has expected columns', function () {
    $columns = [
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'type',
        'status',
        'featured_media_id',
        'parent_id',
        'menu_order',
        'comment_status',
        'published_at',
        'deleted_at',
    ];

    expect(Schema::hasColumns('posts', $columns))->toBeTrue();
});

test('post slug is unique per type', function () {
    expect(Schema::hasIndex('posts', ['type', 'slug']))->toBeTrue();
});

test('term slug is unique per taxonomy', function () {
    expect(Schema::hasIndex('terms', ['taxonomy_id', 'slug']))->toBeTrue();
});
