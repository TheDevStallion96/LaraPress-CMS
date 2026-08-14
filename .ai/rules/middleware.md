---
paths:
  - 'app/Http/Middleware/**'
---

# Middleware

## AdminAccessMiddleware must not redirect unverified users to login
The User model does NOT implement MustVerifyEmail (commented out in the starter kit), so the `verified` middleware is a no-op. AdminAccessMiddleware must only check authentication + the `read` capability. If it redirects users with `email_verified_at = null` to `route('login')`, it creates an infinite loop: Fortify's `guest` middleware on /login redirects authenticated users back to `/admin/dashboard`. Any authenticated user (verified or not) with read may access /admin.
