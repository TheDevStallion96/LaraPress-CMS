# P8 — Public Site (F9, Inertia + SSR)

Deliverable: Public site: archives, search, pagination, SEO, error pages. Depends on P1, P4, P7.

## Routes

`routes/public.php`:

- `/` home (front page setting).
- `/posts/{post:slug}`, `/pages/{page:slug}`.
- `/category/{term:slug}`, `/tag/{term:slug}`.
- `/author/{user}`.
- `/search?q=`.
- 404 + error pages.

All only show `published` (non-future, non-private) posts.

## Pages

`resources/js/pages/Public/`:

- `Home.vue`
- `SinglePost.vue`
- `SinglePage.vue`
- `Archive.vue`
- `Author.vue`
- `Search.vue`
- `Error404.vue`

Reuse `BlockRenderer` + `Pagination` component.

## SEO

- `<Head>` titles/descriptions/OG tags from post meta (`post_meta` `_seo_*` keys) + site settings.

## Tests

`tests/Feature/Public`:

- Each route 200 + content.
- Pagination bounds.
- 404.
- Search hits.
- Visibility scoping (private/future hidden).
