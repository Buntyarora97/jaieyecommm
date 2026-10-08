# Jain Eye Hospital & Laser Centre — Complete Website (Core PHP + MySQL)

A complete, from-scratch rebuild of **jaineye.com** — ultra-premium light-theme healthcare website with a full Admin CMS, built in **Core PHP 8 + MySQL** for standard **cPanel shared hosting**. No frameworks, no Composer, no VPS needed.

## What's Inside

| Area | Details |
|---|---|
| Frontend | 12-section homepage, 8-section About, doctor directory + profiles, speciality & treatment pages, technology, patient resources, blog, gallery, reels, contact, appointment |
| Navigation | 8 items, 2 four-column mega menus + dropdowns — fully DB-driven (editable in admin) |
| Admin CMS | `/admin/` — dashboard, appointments, enquiries, doctors, specialities, treatments, technology, blog, FAQs, testimonials, gallery, reels, media, awards, academics, community, insurance, page sections, navigation, footer, SEO, redirects, media library, users, roles, settings, audit logs |
| Database | `database/schema.sql` — 36 tables, indexes, FKs, utf8mb4, seeded with navigation, specialities, treatments, doctors, FAQs & SEO defaults |
| SEO | Unique meta per route, canonical, OG tags, Hospital/Physician/BlogPosting schema, XML sitemap (`/sitemap.xml`), HTML sitemap, robots.txt, 301 redirect manager, 404 handling |
| Security | PDO prepared statements, bcrypt passwords, CSRF on every form, login rate-limiting + lockout, role-based permissions, upload validation (MIME + size), honeypot spam traps, secure session cookies, security & audit logs |

## Quick Start (5 minutes)

1. **cPanel → MySQL Databases**: create database `jaineye_cms` + user, grant ALL PRIVILEGES.
2. **phpMyAdmin → Import**: select the database, import `database/schema.sql`.
3. **Upload files** to `public_html/` (File Manager → Upload the ZIP → Extract).
4. **Edit `config/config.php`**: set `DB_NAME`, `DB_USER`, `DB_PASS`.
5. **Login**: visit `https://yourdomain.com/admin/` → `admin@jaineye.com` / `Admin@123` — **change this password immediately** (Admin → Users → Edit).

Full instructions: see `docs/01-deployment-guide.md`.

## Requirements

- PHP 8.0+ with PDO MySQL (any standard cPanel host)
- MySQL 5.7+ / MariaDB 10.3+
- Apache with mod_rewrite (standard on cPanel)

## Default Structure

```
├── index.php            Front controller (clean URLs)
├── sitemap.php          Dynamic XML sitemap
├── .htaccess            Routing + security headers + caching
├── config/config.php    Credentials & constants  ← EDIT THIS
├── includes/            db.php, functions.php, header.php, footer.php
├── pages/               All frontend page templates
├── admin/               Complete Admin CMS
├── assets/              css / js / img
├── uploads/             Media uploads (script execution disabled)
├── database/schema.sql  Full schema + seed data
└── docs/                Deployment, admin, SEO-migration & launch docs
```

## Notes

- Doctor profiles, testimonials, awards and photos are intentionally **not fabricated** — add verified content through the admin panel.
- Replace `assets/img/placeholder.svg` and upload real hospital photos via **Admin → Media Library** and each module's image fields.
- To enable email notifications, set `MAIL_ENABLED` to `true` in `config/config.php` after confirming mail works on your hosting.
