# LaraPress-CMS — WordPress Clone: Feature & Project Plan

A WordPress content management system clone built on the existing Laravel 13 + Inertia v3 + Vue 3.5 + Tailwind v4 + shadcn-vue (reka-ui) starter kit, with Fortify auth, Wayfinder typed routes, and Pest.

## Decisions (settled)

- **Scope**: Core CMS first — posts, pages, taxonomies, block editor, media library, users + roles, comments, menus, settings, public-facing site. Custom post types as an extension layer. Widgets/themes/plugins ecosystems are out of scope.
- **Public site**: Inertia pages rendered via Vue SSR (`@inertiajs/vite`), reusing the same block components as the editor.
- **Roles & permissions**: custom WP-style `roles` table with JSON capability maps + `role_user` pivot. No permission package.
- **Media**: add `spatie/laravel-image` (approved) for thumbnail/responsive variant generation.
- **Auth**: keep Fortify (login, 2FA, passkeys, verification) untouched.

## Architecture Overview

- One codebase, two surfaces:
  - `/admin` panel — Inertia + Vue + shadcn (the "wp-admin" equivalent).
  - Public-facing site — Inertia pages via SSR, reusing block components.
- WordPress-faithful content model: unified `posts` table (type: `post`/`page`/custom) + `post_meta` (EAV), WP-style taxonomies, `revisions`, JSON block-store in `posts.content`.
- Capabilities enforced through Laravel `Gate`/`Policy` and a `CapabilityService`; `Gate::before` grants administrators everything.

---

## Part 1 — Feature Implementation Plan

### F0 — Foundation

- Migrations: `posts`, `post_meta`, `revisions`, `taxonomies`, `terms`, `post_term`, `media`, `comments`, `menus`, `menu_items`, `roles`, `role_user`, `settings`; extend `users` (display_name, first_name, last_name, bio).
- Models + factories + seeders for every table (seed WP roles: administrator, editor, author, contributor, subscriber; sample content).
- Admin shell: `/admin` routes group, `AdminLayout` (reuse existing `AppSidebarLayout` pattern with CMS nav sections), Dashboard page with quick stats (post counts, recent comments).
- Capability map: `config/capabilities.php` (read, edit_posts, publish_posts, edit_others_posts, delete_posts, upload_files, moderate_comments, manage_categories, manage_options, edit_users, manage_menus, …); `CapabilityService` resolves effective capabilities per user.
- Public shell: root template + shared `SiteLayout` (header nav from menus, footer).

### F1 — Posts & Pages

- Unified `PostController` + Form Requests + Policies.
- List with WP-style status filter tabs (All | Published | Draft | Pending | Scheduled | Trash), bulk actions, search, sort.
- Editor: title, slug (auto from title, `Str::slug` + uniqueness per type), excerpt, featured image picker (from Media library), status + visibility (public/private), scheduled publish (`published_at`), trash/restore/delete, parent selector for pages.
- Revisions: snapshot on every update; revision history + restore UI.
- Scheduler: `posts:publish-scheduled` artisan command (every minute; lazy check on read) to flip `future` → `published`.
- Tests: CRUD, slug collisions, status transitions, scheduling, trash/restore, revision restore, authorization per role.

### F2 — Taxonomies

- WP-style: `taxonomies` (category, tag, extensible) → `terms` (name, slug, parent, description, count) → `post_term` pivot.
- Admin CRUD for Categories (hierarchical tree) and Tags (flat), assignable from the post editor (multi-select with tag autocomplete).
- Counts maintained (increment/decrement on save).
- Tests: term CRUD, hierarchy, assignment, count accuracy, dedup.

### F3 — Media Library

- Uploads to the configured filesystem; `media` table stores filename, mime, dimensions, size, alt/caption/title/description.
- `spatie/laravel-image` generates thumbnails + responsive sizes on upload; variants recorded on the media row.
- Grid + list view, search/filter by type, delete, bulk delete, edit metadata dialog.
- "Insert into editor" / "Set as featured image" flows push selected media into the block editor.
- Tests: upload (Storage fake), mime whitelist, size limits, thumbnail generation, metadata update, delete cleanup.

### F4 — Block Editor (core)

- Content format: `posts.content` = JSON array of blocks: `[{ id, type, props }]` (e.g. `paragraph`, `heading`, `image`, `quote`, `list`, `code`, `separator`, `columns`, `button`, `gallery`, `embed`).
- PHP registry: `app/Blocks/Block` abstract (name, title, icon, schema/validation) + `BlockRegistry`; used for validation and server-side rendering fallback.
- Vue registry: `resources/js/blocks/<name>/Edit.vue` + `Renderer.vue`; `blockRegistry.ts` maps type → components.
- Editor page: shadcn-based canvas with block toolbar on hover, "+" add-block palette, drag-and-drop reorder (native DnD via `@vueuse`), right-hand inspector for block props, debounced auto-save to draft, Preview link to the public route.
- SSR rendering: public single pages resolve each block through the same Vue registry (works with `@inertiajs/vite` SSR out of the box).
- Tests: block registry integrity, content validation, round-trip save/render, autosave, empty/unknown-block handling.

### F5 — Users & Roles

- `/admin/users` CRUD: create (via Fortify flow), edit profile fields, assign roles, delete.
- `/admin/roles`: list/edit capability checkboxes (capability map driven), create custom roles.
- Wire capabilities into `PostPolicy`, `MediaPolicy`, `CommentPolicy`, etc.; `can:` route middleware; RoleMiddleware for admin sections.
- Tests: capability resolution, policy enforcement per role, gate overrides for administrator.

### F6 — Comments

- Public: threaded comment form on single posts (respects `comment_status`), rate-limited, held for moderation or auto-approved per setting.
- Admin: list with tabs (Pending | Approved | Spam | Trash), approve/unapprove/mark-spam/delete/trash, reply.
- Tests: submission, threading, moderation flow, spam tab, permission gates.

### F7 — Menus

- `menus` (name, location) → `menu_items` (type: post/page/term/custom; parent/order; label; url).
- Admin: menu builder (reorder, nest, add items from content picker + custom links), assign to theme location(s).
- Public header/footer render from the assigned menu via `SiteLayout`.
- Tests: CRUD, ordering/nesting, location assignment, public rendering.

### F8 — Settings

- `settings` key-value store with groups (General: site title/tagline/URL; Reading: posts-per-page, front page choice; Writing: default category; Discussion: comment defaults; Permalinks: slug structure).
- `/admin/settings/{group}` pages with a generic settings form component.
- Public site reads settings (site title in `<Head>`, posts-per-page pagination, comment toggles, front page as static page or latest posts).
- Tests: get/set, caching, wiring into public pages.

### F9 — Public Site (Inertia + SSR)

- Home (front page setting: latest posts or static page), Post/Page single (block render), archive pages for categories/tags, author archives, pagination, search, 404/error pages.
- SEO basics: `<Head>` titles/descriptions/OG tags from post meta.

### F10 — Hardening & Polish

- Validation everywhere via Form Requests; rate limits on comments/auth; XSS-safe block rendering (sanitize props, allowlist).
- Full search via SQL `LIKE` initially; `scout`/`tntsearch` noted as a later option (needs approval).
- Dashboard metrics, empty states, skeleton loaders for deferred props.

---

## Data Model (tables added)

| Table | Key columns |
|---|---|
| `posts` | author_id, title, slug, excerpt, content (JSON), type, status, featured_media_id, parent_id, menu_order, comment_status, published_at |
| `post_meta` | post_id, key, value (EAV) |
| `revisions` | post_id, author_id, title, excerpt, content, status |
| `taxonomies` | name (category/tag/custom) |
| `terms` | taxonomy_id, name, slug, parent_id, description, count |
| `post_term` | post_id, term_id, term_order |
| `media` | user_id, filename, path, disk, mime_type, width, height, size, alt, caption, conversions |
| `comments` | post_id, parent_id, user_id, author_name/email/url, content, status |
| `menus` / `menu_items` | name, location / menu_id, parent_id, order, type, target_id, url, label |
| `roles` / `role_user` | name, slug, capabilities (JSON) / role_id, user_id |
| `settings` | key (unique), value, autoload |

---

## Part 2 — Project Plan (execution order)

Each phase = schema/model + backend + Vue pages + **Pest tests**, then `pint`, `phpstan`, `eslint`, `vue-tsc`, `npm run build`.

| Phase | Deliverable | Depends on |
|---|---|---|
| **P0** | Foundation: migrations, models/factories/seeders, roles+capabilities, admin & public shells, dashboard | — |
| **P1** | Posts & Pages CRUD, statuses, slugs, revisions, scheduling command | P0 |
| **P2** | Taxonomies (categories/tags) + editor assignment | P1 |
| **P3** | Media Library + spatie/laravel-image variants + featured image | P1 |
| **P4** | Block editor (registry, ~8 core blocks, inspector, DnD, autosave, SSR render) | P1, P3 |
| **P5** | Users & Roles admin + policies wired to capabilities | P0 |
| **P6** | Comments (public submit + moderation) | P1, P5 |
| **P7** | Menus + Settings groups, wired into public layout | P1 |
| **P8** | Public site: archives, search, pagination, SEO, error pages | P1, P4, P7 |
| **P9** | Hardening: rate limits, XSS audit, dashboard polish, full `ci:check` green | all |

## Quality Gates

- `php artisan test --compact` per phase
- `composer types:check` (phpstan)
- `npm run types:check` (vue-tsc)
- `npm run lint:check` (eslint)
- `vendor/bin/pint` (code style)
- Block rendering + content round-trip get dedicated unit tests; authorization covered by feature tests per role.

## Dependencies

- **Approved**: `spatie/laravel-image`.
- Everything else uses built-ins (`Str::slug`, JSON columns, scheduler). Any additional package requires approval.

## Conventions (must follow)

- Existing layouts (`AppSidebarLayout`), single-root Vue components.
- Wayfinder for all route access from Vue; `Inertia::render` page naming.
- Form Requests for validation; factories in tests; PHPDoc over comments; Pest for all tests.
