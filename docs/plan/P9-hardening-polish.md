# P9 — Hardening & Polish (F10)

Deliverable: rate limits, XSS audit, dashboard polish, full `ci:check` green. Depends on all.

## Hardening

- Form Requests everywhere (audit).
- Rate limits on comments/auth.
- Block-prop sanitization + allowlist (XSS-safe render).
- Full search via SQL `LIKE` initially; `scout`/`tntsearch` noted as a later option (needs approval).

## Polish

- Dashboard metrics.
- `EmptyState` components.
- Skeleton loaders for deferred props.

## Quality gates

- `php artisan test --compact` (full suite).
- `vendor/bin/pint --format agent`.
- `composer types:check` (phpstan).
- `npm run types:check` (vue-tsc).
- `npm run lint:check` (eslint).
- `npm run build` + `npm run build:ssr`.
- `composer ci:check` fully green.
