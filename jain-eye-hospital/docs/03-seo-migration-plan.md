# SEO Migration Plan — Rebuilding jaineye.com Safely

## Phase 1 — Before Launch (Old Site Crawl)
1. Crawl the current site with Screaming Frog (free up to 500 URLs) or export URLs from Google Search Console → Pages report.
2. Record for every URL: **URL, title tag, meta description, indexed status, top landing pages by organic clicks** (GSC → Performance → Pages).
3. List any existing redirects.

## Phase 2 — URL Mapping
1. Map every valuable old URL to its closest new URL in a spreadsheet:
   | Old URL | New URL | Action |
   |---|---|---|
   | /about.php | /about-us | 301 |
   | /cataract-surgery.php | /treatments/cataract-surgery | 301 |
   | /some-old-blog | /blog/some-old-blog | 301 or recreate content |
2. Pages with traffic or backlinks **must** get a 301 — never leave them to 404.
3. Preserve existing high-performing titles/descriptions by entering them in **Admin → SEO Manager** for the matching new routes.

## Phase 3 — Staging
1. Deploy to a staging subdomain (e.g. `new.jaineye.com`) with `noindex` set in Admin → SEO Manager robots field for all routes, plus password protection.
2. Test everything (see launch checklist). Crawl staging with Screaming Frog; fix broken links and missing meta.

## Phase 4 — Launch
1. Back up old files + old database.
2. Deploy new site to production; remove `noindex`; enable HTTPS redirect.
3. Add all 301 redirects via **Admin → Redirects** (or bulk-insert into the `redirects` table).
4. Submit `/sitemap.xml` in Search Console; request indexing for the homepage and key pages.

## Phase 5 — After Launch (4 weeks)
- **Week 1**: Check GSC Coverage daily for crawl errors/404 spikes; add missing 301s.
- **Week 2–4**: Monitor rankings/clicks for the top 20 landing pages. Minor fluctuations are normal; sustained drops on a page usually mean a missing redirect or changed content — investigate.
- Keep the old-site backup for at least 3 months.

## Hard Rules
- No fabricated ratings, reviews or physician credentials in content or schema.
- No doorway pages (duplicate city/service pages with swapped keywords).
- One H1 per page; unique title + description per route; descriptive image alt text.
