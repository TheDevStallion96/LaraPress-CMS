# P4 — Block Editor (F4)

Deliverable: Block registry, ~8–10 core blocks, inspector, DnD, autosave, SSR render. Depends on P1, P3.

## Content format

`posts.content` = `[{ id, type, props }]`.

## PHP registry

- `app/Blocks/Block` (abstract: `name()`, `title()`, `icon()`, `schema(): array`, `validate(array $props): array`).
- `BlockRegistry` — register/retrieve/validate; unknown block → error list; sanitizes props.
- Blocks: `paragraph`, `heading`, `image`, `quote`, `list`, `code`, `separator`, `columns`, `button`, `gallery`, `embed` (10 total).

## Vue registry

- `resources/js/blocks/blockRegistry.ts` (type → `{ Edit, Renderer, title, icon }`).
- `resources/js/blocks/<name>/Edit.vue` + `Renderer.vue` per block.
- Shared `BlockCanvas` renders via `component :is`.

## Editor UI

- `Admin/Posts/BlockEditor.vue`: canvas with hover toolbar (move/duplicate/delete), `AddBlockPalette` ("+"), native HTML5 DnD reorder (small `useSortableList` composable, no new package), right-hand `BlockInspector` (per-block prop inputs), debounced autosave to draft, **Preview** link to public route (preview key / `?preview=` guard).

## SSR

- Public single pages resolve blocks through the same Vue registry — works with `@inertiajs/vite` SSR (`npm run build:ssr`).
- PHP `BlockRegistry` used as validation + optional no-JS fallback render.

## Tests

`tests/Unit/Blocks`:

- Registry integrity (name uniqueness, all blocks registered).
- `validateContent` (valid/unknown/missing-id).
- Round-trip save → render.
- Autosave endpoint.
- Empty + unknown-block handling.
