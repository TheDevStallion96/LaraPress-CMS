# P7 — Menus + Settings (F7, F8)

Deliverable: Menus + Settings groups, wired into public layout. Depends on P1.

## F7 — Menus

### Backend

- `MenuController` + `MenuItemService` (`app/Services/MenuItemService.php`): tree build, order/nesting, resolve target → url/label from post/page/term; location assignment.

### Frontend

- `Admin/Menus/Index.vue`: create/rename menus, `MenuBuilder` (reorder/nest via DnD, add items from content picker — posts/pages/categories/tags — and custom link), location dropdown (e.g. `primary`, `footer`).

### Public

- `SiteLayout` header/footer render assigned menus through `MenuService` (shared prop).

### Tests

`tests/Feature/Admin/Menus`:

- CRUD.
- Ordering/nesting persistence.
- Location assignment (single per location).
- Public rendering.

## F8 — Settings

### Backend

- `Setting` model + `SettingsService` (`app/Services/SettingsService.php`): get/set, autoload cache via `cache()->remember`, invalidation on set.
- `SettingController` per group (General/Reading/Writing/Discussion/Permalinks) + `SettingFormRequest`.
- Defaults seeded via `SettingsSeeder` (site title/tagline, posts_per_page, front page choice, comment defaults, default category).

### Frontend

- `Admin/Settings/{Group}.vue` using generic `SettingsForm` (schema-driven inputs from `config/settings-groups.php`).

### Public wiring

- Site title in `<Head>`, posts-per-page pagination, comment toggles, front page = static page or latest posts.

### Tests

`tests/Feature/Admin/Settings` + `tests/Feature/Public`:

- Get/set.
- Cache write + invalidate.
- Defaults present.
- Public wiring (pagination count, front-page choice).
