# Testing Report

## Automated Checks Performed (build-time)
| Test | Result |
|---|---|
| PHP syntax lint — all 51 PHP files (PHP 8.3 `php -l`) | **PASS — 51/51, 0 errors** |
| Frontend render smoke test — all 28 page templates rendered with stub data, watched for warnings/notices/fatals | **PASS — 28/28** |
| Admin render smoke test — all 29 admin modules (list + add/edit forms) | **PASS — 29/29** |
| Admin login page render | **PASS** |
| MySQL schema parse — all 64 DDL/seed statements validated against MySQL grammar | **PASS — 64/64** |
| Seeded mega-menu links JSON validity | **PASS** |

## Security Review (code-level)
- All database access via PDO prepared statements; no string-interpolated queries with user input.
- All output escaped via `htmlspecialchars` helper; admin-authored blog HTML is the only intentional raw-HTML field.
- CSRF tokens required on every POST form (public + admin).
- Passwords hashed with `password_hash` (bcrypt); login lockout after 5 failed attempts; security log records failures.
- Upload validation: real MIME sniffing, extension whitelist, size limits, random filenames, PHP execution disabled in `/uploads`.
- Session cookies: HttpOnly, SameSite=Lax, Secure flag when HTTPS.

## Manual QA Required on Staging (not yet executed)
The following must be tested on the live hosting environment before launch — do not mark as passed until actually performed:
- [ ] Real MySQL import + end-to-end page loads
- [ ] Appointment + contact form submissions writing to DB and appearing in admin
- [ ] Admin login with seeded credentials, password change, role permission enforcement
- [ ] Image/video upload on the target host (folder permissions)
- [ ] Mail delivery (if MAIL_ENABLED)
- [ ] Mobile drawer + mega menus on physical devices
- [ ] Lighthouse run (target LCP ≤ 2.5s, INP ≤ 200ms, CLS ≤ 0.1)
- [ ] 301 redirects and 404 handling on Apache/cPanel
