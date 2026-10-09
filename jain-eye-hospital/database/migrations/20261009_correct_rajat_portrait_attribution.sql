-- Existing installations only. The image itself identifies Dr Rahul Jain,
-- so remove it from Dr Rajat Jain's profile until his portrait is verified.
UPDATE doctors
SET photo = NULL
WHERE slug = 'dr-rajat-jain'
  AND photo = 'assets/img/rajat-jain-3.webp';

UPDATE gallery_items
SET is_active = 0
WHERE title = 'Dr Rajat Jain'
  AND image = 'assets/img/rajat-jain-3.webp';
