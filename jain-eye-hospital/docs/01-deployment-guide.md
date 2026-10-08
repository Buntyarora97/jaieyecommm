# Deployment Guide — cPanel Shared Hosting

## 1. Create the MySQL Database
1. cPanel → **MySQL® Databases**.
2. Create database: e.g. `jaineye_cms`.
3. Create user with a strong password: e.g. `jaineye_user`.
4. **Add User To Database** → grant **ALL PRIVILEGES**.

## 2. Import the Schema
1. cPanel → **phpMyAdmin** → select your database.
2. Tab **Import** → choose `database/schema.sql` → **Go**.
3. Confirm 36 tables were created and seed data exists (check `navigation_items`, `specialities`, `admins`).

## 3. Upload the Files
1. cPanel → **File Manager** → `public_html/`.
2. If migrating, first back up the old site: compress the current `public_html` contents and download the ZIP, and export the old database via phpMyAdmin.
3. Upload the project ZIP → **Extract** → ensure `index.php` sits directly in `public_html/` (not in a subfolder).

## 4. Configure Credentials
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_cpanel_dbname');
define('DB_USER', 'your_cpanel_dbuser');
define('DB_PASS', 'your_strong_password');
define('SITE_URL', 'https://jaineye.com');
```

## 5. Enable SSL + HTTPS Redirect
1. cPanel → **SSL/TLS Status** → run AutoSSL (free).
2. Once active, uncomment the HTTPS redirect lines in `.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 6. Permissions
- Folders: `755`, Files: `644` (cPanel default).
- Ensure `uploads/` is writable (`755` works on most hosts; use `775` only if uploads fail).

## 7. First Admin Login
1. Visit `https://yourdomain.com/admin/`.
2. Login: `admin@jaineye.com` / `Admin@123`.
3. **Immediately**: Admin → Users → Edit → set a new strong password and your real email.

## 8. Email / SMTP
- The CMS uses PHP `mail()` when `MAIL_ENABLED = true` in config.
- On cPanel, create the mailbox `no-reply@jaineye.com` first. Test by submitting the appointment form.
- If mail is unreliable, install a mail plugin later or use cPanel's SMTP with a small PHPMailer addition.

## 9. Post-Deployment Tests
- [ ] Homepage loads, all 12 sections render
- [ ] All 8 nav items, both mega menus, mobile drawer
- [ ] Doctor, speciality, treatment, blog detail pages
- [ ] Appointment form → appears in Admin → Appointments with status "New"
- [ ] Contact form → appears in Admin → Contact Enquiries
- [ ] Admin login, CRUD on one module, image upload
- [ ] `/sitemap.xml` loads; `robots.txt` loads
- [ ] A test redirect in Admin → Redirects works (301)
- [ ] 404 page shows for a fake URL

## 10. Search Console + Analytics
1. Verify the domain in Google Search Console (HTML file or DNS method).
2. Submit `https://jaineye.com/sitemap.xml`.
3. Add your analytics snippet before `</head>` in `includes/header.php`.

## 11. Backups & Rollback
- **Backup**: cPanel → Backup Wizard (files) + phpMyAdmin export (DB). Schedule weekly.
- **Rollback**: restore the old `public_html` ZIP + old DB export created in step 3.
