# P5 — Users & Roles (F5)

Deliverable: Users & Roles admin + policies wired to capabilities. Depends on P0.

## Backend

- `UserController` (admin): index/create/edit/update/delete.
- Create via existing Fortify `CreateNewUser`; edit adds profile fields (`display_name`, first/last, `bio`) + role assignment (`role_user` sync).
- `UserPolicy` (`edit_users`/`delete_users`; cannot self-delete).
- `RoleController`: index (list + capability matrix), store/update/destroy (custom roles; protect the 5 seeded). `RoleFormRequest`.

## Wiring

- Policies (Post/Media/Comment) read via `CapabilityService`.
- `can:` middleware on admin route groups.

## Frontend

- `Admin/Users/Index.vue`: table + create/edit dialog.
- `Admin/Roles/Index.vue`: capability checkboxes grouped by area, driven by `config/capabilities.php`.

## Tests

`tests/Feature/Admin/Users` + `tests/Feature/Admin/Roles`:

- Capability resolution.
- Per-role policy enforcement.
- Administrator gate override.
- Role CRUD + custom role round-trip.
- Self-delete guard.
