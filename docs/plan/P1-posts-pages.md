# P1 — Posts & Pages (F1)

Deliverable: Posts & Pages CRUD, statuses, slugs, revisions, scheduling command. Depends on P0.

## Backend

- `PostController` (index/create/store/edit/update/preview/trash/restore/forceDelete).
- `StorePostRequest` / `UpdatePostRequest` (Form Requests; title required, slug auto from title via `SlugService` with per-type uniqueness, status transitions, `published_at` when status=future, parent validation for pages).
- `PostPolicy` mapped to capabilities: create → `edit_posts`, publish → `publish_posts`, edit others → `edit_others_posts`, delete → `delete_posts` + trash-specific caps.
- `SlugService` (`app/Services/SlugService.php`): `Str::slug` + unique per type.
- `RevisionService` (`app/Services/RevisionService.php`): snapshot (title, excerpt, content, status, author) on every create/update; restore.
- `posts:publish-scheduled` artisan command (future → published when `published_at <= now`), scheduled every minute; read-side lazy check scope (`wherePublished` / `orFuture`).

## Frontend

- `Admin/Posts/Index.vue`: status filter tabs (All | Published | Draft | Pending | Scheduled | Trash; Published includes Private), search (title/excerpt LIKE), sort (date/title/menu_order), bulk actions (trash/restore/delete per capability), generic `DataTable` + `StatusBadge` + `BulkActionsBar` components.
- `Admin/Posts/Edit.vue` (+ Create): title, `SlugInput`, excerpt, `FeaturedImagePicker` (from F3), status/visibility selects (private = `status=private`), `published_at` picker when scheduling, parent selector (pages), taxonomy picker (F2), and `BlockEditor` (F4).
- Debounced autosave → `draft` via `admin.posts.update` / dedicated autosave endpoint.
- `Admin/Posts/Revisions.vue`: history panel with diff view + restore.

## Tests

`tests/Feature/Admin/Posts`:

- CRUD per type.
- Slug collision/dedup.
- Status transitions (+private).
- Scheduling via `$this->travel()`.
- Trash/restore/force-delete.
- Revision snapshot + restore.
- Policy enforcement per role (editor/author/contributor matrix).
