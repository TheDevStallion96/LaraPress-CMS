# P6 — Comments (F6)

Deliverable: Comments (public submit + moderation). Depends on P1, P5.

## Backend

- `PublicCommentController@store` on single posts (respects `comment_status`, only published posts; guest name/email/url or logged-in user), `throttle:...` rate limit, holds for moderation or auto-approves per setting.
- `CommentController` (admin): tabs (Pending | Approved | Spam | Trash), approve/unapprove/mark-spam/trash/delete, reply (creates child).
- `CommentPolicy` (`moderate_comments`; edit own).
- `CommentService` (`app/Services/CommentService.php`): moderation flow, spam heuristics placeholder, counts.

## Frontend

- `CommentForm` component with threading (public).
- `Admin/Comments/Index.vue`: moderation tabs + actions.

## Tests

`tests/Feature/Admin/Comments` + `tests/Feature/Public/Comments`:

- Submission (guest + auth).
- Threading.
- Moderation pending → approved.
- Spam tab.
- Rate limit.
- Permission gates.
