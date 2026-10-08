# Admin CMS Guide

Login: `/admin/` · Roles: **Super Admin** (everything), **Editor** (content), **Staff** (appointments + enquiries only).

## Daily Workflows

### Appointments
Admin → **Appointments** → filter by status → **View** → change status (New → Contacted → Pending Confirmation → Confirmed → Completed / Cancelled). Every change is recorded in the status history with your name and timestamp. Online submissions are **requests**, never auto-confirmed.

### Contact Enquiries
Admin → **Contact Enquiries** → **View** → update status and add internal notes for the team.

### Doctors
Admin → **Doctors** → Add/Edit. Fill designation, qualifications, specialisation, biography, education and a portrait photo. Only publish **verified** credentials. Slug auto-generates from the name.

### Specialities & Treatments
Each speciality page supports Overview, Symptoms, Conditions, Diagnostics, Treatment Options, Technology, What to Expect and Recovery — all editable. Link treatments to their speciality via the dropdown.

### Blog
Posts support **Draft → In Review → Published** workflow, categories, featured image, excerpt and medical reviewer credit. Only publish clinically reviewed content.

### Media Library
Admin → **Media Library** — upload images once, reuse anywhere. Uploads are MIME-validated; scripts are blocked from executing in `/uploads`.

### Navigation & Footer
Admin → **Navigation Manager** — edit the 8 top items, dropdown children and mega-menu columns (links columns use simple JSON: `[{"label":"Name","url":"/page"}]`). Footer columns under **Footer Links**.

### SEO Manager & Redirects
- **SEO Manager**: set meta title/description/canonical/OG image per route (e.g. `/doctors`).
- **Redirects**: add `old → new` 301 mappings. When migrating from the old site, add every old URL here.

### Users & Roles
Admin → **Users** to create staff logins; **Roles** to tick which modules each role can access.

### Audit Logs
Every create/update/delete/login is logged with admin name, time and IP — see **Audit Logs**.
