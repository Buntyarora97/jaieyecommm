# Launch Checklist

## Pre-Launch
- [ ] Old files backed up (ZIP downloaded)
- [ ] Old database exported (.sql downloaded)
- [ ] Old URLs crawled and redirect map prepared
- [ ] `config/config.php` updated with production DB credentials
- [ ] `SITE_URL` set to https://jaineye.com
- [ ] First administrator created through the one-time setup; no shared default password exists
- [ ] Supplied logo and clinic images load on desktop and mobile
- [ ] Confirm the map pin opens the intended hospital entrance
- [ ] Hospital approves mission/vision copy, doctor biographies and all clinical descriptions
- [ ] Hospital reviews Terms, Privacy, Accessibility and Medical Disclaimer wording
- [ ] Doctor profiles completed with verified credentials only
- [ ] Testimonials published only if genuine and approved
- [ ] All draft/review blog posts reviewed before publishing
- [ ] SSL certificate active; HTTPS redirect enabled in `.htaccess`
- [ ] Test appointment + contact form end-to-end, including consent, one-time thank-you and saved records
- [ ] Confirm staff can see submissions in the CMS
- [ ] Test email delivery before enabling email notifications
- [ ] 301 redirects added for every old URL
- [ ] robots.txt + sitemap.xml reachable
- [ ] 404 page tested
- [ ] Mobile + tablet + desktop spot-checks done
- [ ] Lighthouse performance/accessibility run recorded

## Launch Day
- [ ] Deploy files to `public_html`
- [ ] Import database
- [ ] Smoke test: homepage, one doctor, one speciality, one blog post, both forms
- [ ] Submit sitemap in Google Search Console
- [ ] Remove any staging noindex/password protection

## Post-Launch (Week 1–4)
- [ ] Monitor GSC coverage & 404s daily (week 1), then weekly
- [ ] Check appointment notifications reach staff
- [ ] Verify top landing pages retain rankings
- [ ] Schedule weekly file + database backups
- [ ] Keep old-site backup for 3 months minimum
