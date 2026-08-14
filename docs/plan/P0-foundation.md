# P0 — Foundation (F0)

Deliverable: migrations, models/factories/seeders, roles + capabilities, admin & public shells, dashboard.

## Migrations (dependency order; all new tables get `created_at/updated_at`)

1. `add_profile_fields_to_users_table` — `display_name`, `first_name`, `last_name`, `bio` (nullable). `display_name` optional fallback to `name`.
2. `roles` (`id, name, slug unique, capabilities json`) + `role_user` (`role_id, user_id`, composite PK).
3. `taxonomies` (`name` unique; seeds `category`, `tag`).
4. `terms` (`taxonomy_id` FK cascade, `name`, `slug`, `parent_id` self FK, `description` nullable, `count` unsigned default 0; unique index `(taxonomy_id, slug)`).
5. `media` (`user_id` FK, `filename`, `path`, `disk`, `mime_type`, `width`, `height`, `size`, `alt`, `caption`, `title`, `description`, `conversions` json default `[]`).
6. `posts` (`author_id` FK users, `title`, `slug`, `excerpt` nullable, `content` json default `[]`, `type` default `post` idx, `status` default `draft` idx, `featured_media_id` FK nullable, `parent_id` self FK nullable, `menu_order` int, `comment_status` default `open`, `published_at` timestamp nullable; unique `(type, slug)`; `softDeletes`).
7. `post_meta` (`post_id` FK cascade, `key`, `value` text; idx `(post_id, key)`).
8. `revisions` (`post_id` FK cascade, `author_id` FK nullable, `title`, `excerpt`, `content` json, `status`).
9. `post_term` (`post_id`, `term_id`, `term_order`; composite PK).
10. `comments` (`post_id` FK cascade, `parent_id` self FK, `user_id` FK nullable, `author_name/email/url` nullable, `content`, `status` default `pending` idx).
11. `menus` (`name`, `location` nullable) + `menu_items` (`menu_id` FK cascade, `parent_id` self FK, `order`, `type`, `target_id` nullable, `url` nullable, `label`).
12. `settings` (`key` unique, `value` text nullable, `autoload` bool default true).

## Models

Match `User.php` style: PHP attribute `#[Fillable]`/`#[Hidden]`, `casts()`, `@property` PHPDoc.

- `Role`, `Post`, `PostMeta`, `Revision`, `Taxonomy`, `Term`, `Media`, `Comment`, `Menu`, `MenuItem`, `Setting` + factory for each (only the ones factories will use: Post, Term, Taxonomy, Media, Comment, Menu, MenuItem, Setting, Role).
- `User` gains `displayName` attribute + `roles(): BelongsToMany`.

## Capabilities

- `config/capabilities.php` (`'capabilities' => [name => label]` + `'roles' => ['administrator' => [...]]` maps, WP-faithful).
- `CapabilityService` (`app/Services/CapabilityService.php`): `allFor(User $user): array`, `can(string $cap, ?User $user): bool`, cached per request.
- `Gate::before` grants all caps to users holding `administrator`.
- `RoleSeeder` seeds the 5 WP roles (administrator, editor, author, contributor, subscriber).
- `AdminAccessMiddleware` (any authenticated verified user with `read`).

## Admin shell

- `routes/admin.php` (prefix `/admin`, `auth|verified`), `AdminLayout` (wraps existing `AppLayout`, groups admin nav), `Admin/Dashboard.vue` with quick stats (post counts per status, recent comments, recent posts, version/site info).
- Migrate existing `dashboard`, `settings/*` routes under `/admin` (update `routes/settings.php` → `admin/settings`, keep nested `SettingsLayout`, add redirect `/settings` → `/admin/settings/profile`).
- Admin nav: extend `AppSidebar.vue` `mainNavItems` into grouped/collapsible sections (Posts, Pages, Media, Comments, Users, Roles, Menus, Settings, View Site). Extend `NavMain.vue` to render `children` via existing `SidebarMenuSub` collapsible primitives. New `NavSection.vue` wrapper.
- Wayfinder: regenerate after every route batch.

## Public shell

- `SiteLayout.vue` (header from assigned menu via `MenuService`, footer, `<Head>`), replaces `Welcome` at `/`.

## Shared Inertia props

`HandleInertiaRequests`: add `site` (autoloaded settings: title, tagline, posts_per_page), `menus` (location → items for header/footer), `auth.capabilities` (for nav gating).

## `app.ts` layout mapping

- `admin/**` → `AppLayout` (+ `SettingsLayout` nested for admin settings), `Public/**` → `SiteLayout`, `Welcome` stays null.

## `bootstrap/app.php`

- Append `Can::class` (`can:` middleware) and new `AdminAccessMiddleware` to web group.

## Tests

`tests/Feature/Admin/Foundation`:

- Schema exists per table (`Schema::hasTable` / column assertions).
- Role/capability resolution per seeded role.
- `Gate::before` admin override.
- Admin section smoke tests (auth user → each nav route OK, guest redirect).
- Settings routes render under `/admin`.
