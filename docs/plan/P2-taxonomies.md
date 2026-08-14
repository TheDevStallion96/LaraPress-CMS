# P2 — Taxonomies (F2)

Deliverable: Taxonomies (categories/tags) + editor assignment. Depends on P1.

## Backend

- `TaxonomyController` (categories + tags share it; hierarchical vs flat driven by taxonomy): index/store/update/destroy.
- `TermFormRequest` + `TermPolicy` (`manage_categories`).
- `TermService` (`app/Services/TermService.php`): slug dedup within taxonomy, `count` increment/decrement on `post_term` sync, tree building.

## Frontend

- `Admin/Taxonomies/CategoryIndex.vue`: tree, reorder/parent select.
- `Admin/Taxonomies/TagIndex.vue`: flat list.
- `TaxTermPicker` component in post editor: category multi-select tree, tag multi-select with autocomplete from existing tags.

## Tests

`tests/Feature/Admin/Taxonomies`:

- Term CRUD.
- Hierarchy / cycle guard.
- Assignment sync counts accuracy.
- Dedup per taxonomy.
