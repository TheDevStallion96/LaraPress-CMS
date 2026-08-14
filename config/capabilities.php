<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Capabilities
    |--------------------------------------------------------------------------
    |
    | A WordPress-faithful list of capabilities that can be granted to roles.
    | Each key is the capability slug used by Gate checks; each value is the
    | human readable label shown in the roles admin.
    |
    */

    'capabilities' => [
        'read' => 'Read',
        'upload_files' => 'Upload Files',
        'edit_files' => 'Edit Files',
        'unfiltered_html' => 'Unfiltered HTML',
        'edit_dashboard' => 'Edit Dashboard',

        'edit_posts' => 'Edit Posts',
        'edit_published_posts' => 'Edit Published Posts',
        'edit_others_posts' => "Edit Others' Posts",
        'delete_posts' => 'Delete Posts',
        'delete_published_posts' => 'Delete Published Posts',
        'delete_others_posts' => "Delete Others' Posts",
        'publish_posts' => 'Publish Posts',

        'edit_pages' => 'Edit Pages',
        'edit_published_pages' => 'Edit Published Pages',
        'edit_others_pages' => "Edit Others' Pages",
        'delete_pages' => 'Delete Pages',
        'delete_published_pages' => 'Delete Published Pages',
        'delete_others_pages' => "Delete Others' Pages",
        'publish_pages' => 'Publish Pages',

        'manage_categories' => 'Manage Categories',
        'manage_terms' => 'Manage Terms',
        'moderate_comments' => 'Moderate Comments',
        'edit_comments' => 'Edit Comments',
        'delete_comments' => 'Delete Comments',
        'manage_menus' => 'Manage Menus',
        'edit_theme_options' => 'Edit Theme Options',
        'manage_options' => 'Manage Options',
        'view_site' => 'View Site',

        'list_users' => 'List Users',
        'create_users' => 'Create Users',
        'edit_users' => 'Edit Users',
        'delete_users' => 'Delete Users',
        'remove_users' => 'Remove Users',
        'promote_users' => 'Promote Users',

        'switch_themes' => 'Switch Themes',
        'manage_plugins' => 'Manage Plugins',
        'manage_network' => 'Manage Network',
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | The default WordPress roles and the capabilities granted to each. The
    | administrator role is special-cased in the Gate and implicitly holds
    | every capability.
    |
    */

    'roles' => [
        'administrator' => [],
        'editor' => [
            'read',
            'upload_files',
            'edit_files',
            'unfiltered_html',
            'edit_dashboard',
            'edit_posts',
            'edit_published_posts',
            'edit_others_posts',
            'delete_posts',
            'delete_published_posts',
            'delete_others_posts',
            'publish_posts',
            'edit_pages',
            'edit_published_pages',
            'edit_others_pages',
            'delete_pages',
            'delete_published_pages',
            'delete_others_pages',
            'publish_pages',
            'manage_categories',
            'manage_terms',
            'moderate_comments',
            'edit_comments',
            'delete_comments',
            'manage_menus',
            'edit_theme_options',
        ],
        'author' => [
            'read',
            'upload_files',
            'edit_files',
            'unfiltered_html',
            'edit_posts',
            'edit_published_posts',
            'delete_posts',
            'delete_published_posts',
            'publish_posts',
        ],
        'contributor' => [
            'read',
            'edit_posts',
            'delete_posts',
        ],
        'subscriber' => [
            'read',
        ],
    ],
];
