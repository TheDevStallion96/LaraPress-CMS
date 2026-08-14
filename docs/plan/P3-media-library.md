# P3 — Media Library (F3)

Deliverable: Media Library + `spatie/image` variants + featured image. Depends on P1.

> **Note**: `spatie/laravel-image` (in the master plan) does not exist on Packagist. Confirmed decision: install `spatie/image` (low-level) + custom `MediaVariantService` writing conversions to `media.conversions` JSON.

## Setup

- Install `spatie/image` (only new dependency; other packages need approval).
- Run `php artisan storage:link`.

## Backend

- `MediaController` (index/store/update metadata/destroy/bulkDestroy).
- `UploadMediaRequest` (mime whitelist, size limit), `MediaPolicy` (upload_files; delete own).
- `MediaService` (`app/Services/MediaService.php`): store on configured disk, extract dimensions/size via `getimagesize`/fileinfo, generate variants with **spatie/image** (`thumb` ~300px, `medium` ~768px, `large` ~1536px) + optional AVIF/webp config, record in `media.conversions` JSON; delete cleanup (files + variants).

## Frontend

- `Admin/Media/Index.vue`: grid/list toggle, search + type filter, dropzone upload, metadata edit dialog (alt/caption/title/description), single + bulk delete.
- **Flows**: "Set as featured image" (returns media row → sets `featured_media_id`) and "Insert into editor" (emits block props to BlockEditor).

## Tests

`tests/Feature/Admin/Media`:

- Upload with `Storage::fake`.
- Mime/size rejection.
- Variant generation + conversions persisted.
- Metadata update.
- Delete removes files + variants.
- Policy.
