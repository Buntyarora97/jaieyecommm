-- ============================================================
-- Jain Eye Hospital & Laser Centre - Complete MySQL Schema
-- Engine: InnoDB | Charset: utf8mb4
-- Import via phpMyAdmin after creating the database in cPanel.
-- ============================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  label VARCHAR(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE role_permissions (
  role_id INT UNSIGNED NOT NULL,
  permission_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  CONSTRAINT fk_rp_perm FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id INT UNSIGNED DEFAULT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_admin_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admin_activity_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id INT UNSIGNED DEFAULT NULL,
  action VARCHAR(120) NOT NULL,
  module VARCHAR(60) DEFAULT NULL,
  details TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_admin (admin_id),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE security_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event VARCHAR(120) NOT NULL,
  details TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_event (event),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE site_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  content MEDIUMTEXT DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_sections (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page VARCHAR(60) NOT NULL,
  section_key VARCHAR(80) NOT NULL,
  title VARCHAR(255) DEFAULT NULL,
  subtitle VARCHAR(255) DEFAULT NULL,
  content MEDIUMTEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_page_section (page, section_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE navigation_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parent_id INT UNSIGNED DEFAULT NULL,
  menu_location ENUM('header','footer') NOT NULL DEFAULT 'header',
  label VARCHAR(120) NOT NULL,
  url VARCHAR(255) NOT NULL,
  has_mega TINYINT(1) NOT NULL DEFAULT 0,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE mega_menu_columns (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nav_item_id INT UNSIGNED NOT NULL,
  column_type ENUM('links','featured') NOT NULL DEFAULT 'links',
  title VARCHAR(160) DEFAULT NULL,
  links_json TEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  heading VARCHAR(200) DEFAULT NULL,
  text VARCHAR(400) DEFAULT NULL,
  cta_label VARCHAR(80) DEFAULT NULL,
  cta_url VARCHAR(255) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_mega_nav FOREIGN KEY (nav_item_id) REFERENCES navigation_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE footer_links (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  column_group VARCHAR(60) NOT NULL DEFAULT 'Quick Links',
  label VARCHAR(120) NOT NULL,
  url VARCHAR(255) NOT NULL,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE specialities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  category VARCHAR(120) DEFAULT NULL,
  short_description VARCHAR(400) DEFAULT NULL,
  overview MEDIUMTEXT DEFAULT NULL,
  symptoms MEDIUMTEXT DEFAULT NULL,
  conditions MEDIUMTEXT DEFAULT NULL,
  diagnostics MEDIUMTEXT DEFAULT NULL,
  treatments_text MEDIUMTEXT DEFAULT NULL,
  technology_text MEDIUMTEXT DEFAULT NULL,
  what_to_expect MEDIUMTEXT DEFAULT NULL,
  recovery MEDIUMTEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  icon VARCHAR(80) DEFAULT NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE doctors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  designation VARCHAR(200) DEFAULT NULL,
  qualifications VARCHAR(255) DEFAULT NULL,
  specialisation VARCHAR(200) DEFAULT NULL,
  biography MEDIUMTEXT DEFAULT NULL,
  education MEDIUMTEXT DEFAULT NULL,
  expertise MEDIUMTEXT DEFAULT NULL,
  fellowships MEDIUMTEXT DEFAULT NULL,
  memberships MEDIUMTEXT DEFAULT NULL,
  awards_text MEDIUMTEXT DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE doctor_specialities (
  doctor_id INT UNSIGNED NOT NULL,
  speciality_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (doctor_id, speciality_id),
  CONSTRAINT fk_ds_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
  CONSTRAINT fk_ds_spec FOREIGN KEY (speciality_id) REFERENCES specialities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE doctor_gallery (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT UNSIGNED NOT NULL,
  image VARCHAR(255) NOT NULL,
  caption VARCHAR(255) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_dg_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE treatments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  speciality_id INT UNSIGNED DEFAULT NULL,
  name VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  short_description VARCHAR(400) DEFAULT NULL,
  overview MEDIUMTEXT DEFAULT NULL,
  procedure_text MEDIUMTEXT DEFAULT NULL,
  benefits MEDIUMTEXT DEFAULT NULL,
  recovery MEDIUMTEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_treat_spec FOREIGN KEY (speciality_id) REFERENCES specialities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE treatment_doctors (
  treatment_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (treatment_id, doctor_id),
  CONSTRAINT fk_td_treat FOREIGN KEY (treatment_id) REFERENCES treatments(id) ON DELETE CASCADE,
  CONSTRAINT fk_td_doc FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE technologies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  category ENUM('diagnostics','laser','surgical','facility') NOT NULL DEFAULT 'diagnostics',
  short_description VARCHAR(400) DEFAULT NULL,
  description MEDIUMTEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference VARCHAR(30) NOT NULL UNIQUE,
  name VARCHAR(160) NOT NULL,
  mobile VARCHAR(20) NOT NULL,
  email VARCHAR(160) DEFAULT NULL,
  doctor_id INT UNSIGNED DEFAULT NULL,
  speciality_id INT UNSIGNED DEFAULT NULL,
  preferred_date DATE DEFAULT NULL,
  preferred_time VARCHAR(60) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  consent TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('New','Contacted','Pending Confirmation','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_created (created_at),
  CONSTRAINT fk_appt_doc FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL,
  CONSTRAINT fk_appt_spec FOREIGN KEY (speciality_id) REFERENCES specialities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE appointment_status_history (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT UNSIGNED NOT NULL,
  old_status VARCHAR(40) DEFAULT NULL,
  new_status VARCHAR(40) NOT NULL,
  changed_by INT UNSIGNED DEFAULT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_appt (appointment_id),
  CONSTRAINT fk_ash_appt FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_enquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  email VARCHAR(160) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  subject VARCHAR(200) DEFAULT NULL,
  message TEXT NOT NULL,
  status ENUM('New','In Progress','Resolved','Closed') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE enquiry_notes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  enquiry_id INT UNSIGNED NOT NULL,
  admin_id INT UNSIGNED DEFAULT NULL,
  note TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_note_enquiry FOREIGN KEY (enquiry_id) REFERENCES contact_enquiries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  description VARCHAR(300) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED DEFAULT NULL,
  author_id INT UNSIGNED DEFAULT NULL,
  medical_reviewer VARCHAR(160) DEFAULT NULL,
  title VARCHAR(220) NOT NULL,
  slug VARCHAR(240) NOT NULL UNIQUE,
  excerpt VARCHAR(400) DEFAULT NULL,
  content MEDIUMTEXT DEFAULT NULL,
  featured_image VARCHAR(255) DEFAULT NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('draft','review','published') NOT NULL DEFAULT 'draft',
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  CONSTRAINT fk_blog_cat FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_post_tags (
  post_id INT UNSIGNED NOT NULL,
  tag_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (post_id, tag_id),
  CONSTRAINT fk_bpt_post FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
  CONSTRAINT fk_bpt_tag FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE faqs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(300) NOT NULL,
  answer TEXT NOT NULL,
  category VARCHAR(100) DEFAULT 'General',
  context VARCHAR(60) DEFAULT 'website',
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE testimonials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(160) NOT NULL,
  context VARCHAR(160) DEFAULT NULL,
  content TEXT NOT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gallery_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) DEFAULT NULL,
  category ENUM('infrastructure','doctors','technology','events','community','media') NOT NULL DEFAULT 'infrastructure',
  image VARCHAR(255) NOT NULL,
  caption VARCHAR(255) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  type ENUM('news','press','event') NOT NULL DEFAULT 'news',
  summary VARCHAR(400) DEFAULT NULL,
  content MEDIUMTEXT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  published_at DATE DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reels (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description VARCHAR(500) DEFAULT NULL,
  doctor_id INT UNSIGNED DEFAULT NULL,
  category VARCHAR(100) DEFAULT NULL,
  video_path VARCHAR(255) DEFAULT NULL,
  thumbnail VARCHAR(255) DEFAULT NULL,
  related_speciality_id INT UNSIGNED DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reel_doc FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE insurance_partners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  notes VARCHAR(300) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  position INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE awards (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(220) NOT NULL,
  organisation VARCHAR(200) DEFAULT NULL,
  award_year VARCHAR(10) DEFAULT NULL,
  description VARCHAR(500) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  position INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE academics (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(220) NOT NULL,
  type VARCHAR(120) DEFAULT NULL,
  description MEDIUMTEXT DEFAULT NULL,
  event_date DATE DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE community_initiatives (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(220) NOT NULL,
  description MEDIUMTEXT DEFAULT NULL,
  event_date DATE DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE seo_metadata (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  route VARCHAR(255) NOT NULL UNIQUE,
  meta_title VARCHAR(255) DEFAULT NULL,
  meta_description VARCHAR(320) DEFAULT NULL,
  canonical_url VARCHAR(255) DEFAULT NULL,
  og_image VARCHAR(255) DEFAULT NULL,
  robots VARCHAR(60) DEFAULT 'index,follow',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE redirects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  old_url VARCHAR(255) NOT NULL UNIQUE,
  new_url VARCHAR(255) NOT NULL,
  status_code INT NOT NULL DEFAULT 301,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  hits INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media_library (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  file_path VARCHAR(255) NOT NULL,
  file_name VARCHAR(200) NOT NULL,
  mime_type VARCHAR(100) DEFAULT NULL,
  file_size INT UNSIGNED DEFAULT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  uploaded_by INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE email_templates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  template_key VARCHAR(100) NOT NULL UNIQUE,
  subject VARCHAR(255) NOT NULL,
  body MEDIUMTEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

INSERT INTO roles (id, name, description) VALUES
(1, 'Super Admin', 'Full access to every module'),
(2, 'Editor', 'Can manage content modules'),
(3, 'Staff', 'Appointments and enquiries only');

INSERT INTO permissions (name, label) VALUES
('appointments.manage','Manage appointments'),
('enquiries.manage','Manage contact enquiries'),
('doctors.manage','Manage doctors'),
('specialities.manage','Manage specialities & treatments'),
('content.manage','Manage pages, blog, FAQs, media'),
('seo.manage','Manage SEO & redirects'),
('settings.manage','Manage settings, users & navigation');

INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, id FROM permissions WHERE name IN
('appointments.manage','enquiries.manage','doctors.manage','specialities.manage','content.manage','seo.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, id FROM permissions WHERE name IN ('appointments.manage','enquiries.manage');

-- Default admin: email admin@jaineye.com / password Admin@123  (CHANGE IMMEDIATELY AFTER FIRST LOGIN)
INSERT INTO admins (role_id, name, email, password_hash) VALUES
(1, 'Site Administrator', 'admin@jaineye.com', '$2y$12$Fqax5NEAtycQPZVsZ5uyQu/IuG6zjEB9sIxlZ6gywRDOAKMlEVV7G');

INSERT INTO site_settings (setting_key, setting_value) VALUES
('hero_eyebrow','ADVANCED SUPER-SPECIALITY EYE CARE'),
('hero_title','Advanced Eye Hospital in Shalimar Bagh, Delhi'),
('hero_secondary','Specialist Eye Care for Every Stage of Life'),
('hero_hindi','आपकी आँखों की सेहत, हमारी प्राथमिकता।'),
('hero_description','Explore comprehensive eye care, specialist consultations and personalised treatment options at Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi.'),
('about_intro','Jain Eye Hospital & Laser Centre is a super-speciality eye care hospital located in Shalimar Bagh, Delhi, offering comprehensive eye evaluation, diagnosis and treatment under one roof.'),
('final_headline','Take the Next Step Towards Better Eye Care');

-- Navigation (header)
INSERT INTO navigation_items (id, parent_id, menu_location, label, url, has_mega, position) VALUES
(1,NULL,'header','Home','/',0,1),
(2,NULL,'header','About Us','/about-us',0,2),
(3,NULL,'header','Our Doctors','/doctors',0,3),
(4,NULL,'header','Specialities','/specialities',1,4),
(5,NULL,'header','Technology','/technology',0,5),
(6,NULL,'header','Patient Resources','/patient-journey',1,6),
(7,NULL,'header','Blog','/blog',0,7),
(8,NULL,'header','Contact Us','/contact-us',0,8);

INSERT INTO navigation_items (parent_id, menu_location, label, url, position) VALUES
(2,'header','About Jain Eye Hospital','/about-us',1),
(2,'header','Mission & Vision','/about-us#mission-vision',2),
(2,'header','Leadership','/about-us#leadership',3),
(2,'header','Our Team','/about-us#our-team',4),
(2,'header','Hospital Infrastructure','/gallery?category=infrastructure',5),
(2,'header','Awards & Recognition','/awards',6),
(2,'header','Academics & Training','/academics',7),
(2,'header','Community Initiatives','/community',8),
(3,'header','All Doctors','/doctors',1),
(3,'header','Dr Arun Kumar Jain','/doctors/dr-arun-kumar-jain',2),
(3,'header','Dr Rajat Jain','/doctors/dr-rajat-jain',3),
(3,'header','Dr Neha Mohan','/doctors/dr-neha-mohan',4),
(3,'header','Book Consultation','/book-appointment',5),
(5,'header','Our Technology','/technology',1),
(5,'header','Diagnostic Technology','/technology#diagnostics',2),
(5,'header','Laser Technology','/technology#laser',3),
(5,'header','Surgical Technology','/technology#surgical',4),
(5,'header','Hospital Facilities','/gallery?category=infrastructure',5),
(5,'header','Technology Gallery','/gallery?category=technology',6);

-- Mega menu: Specialities (nav item 4)
INSERT INTO mega_menu_columns (nav_item_id, column_type, title, links_json, position) VALUES
(4,'links','Cataract & Refractive Care','[
{"label":"Cataract & IOL","url":"/specialities/cataract-iol"},
{"label":"Cataract Surgery","url":"/treatments/cataract-surgery"},
{"label":"LASIK & Refractive Surgery","url":"/specialities/lasik-refractive"},
{"label":"LASIK Assessment","url":"/treatments/lasik-assessment"},
{"label":"Laser Vision Correction","url":"/treatments/laser-vision-correction"},
{"label":"Intraocular Lens Options","url":"/treatments/intraocular-lens-options"}]',1),
(4,'links','Retina & Cornea','[
{"label":"Retina & Uvea","url":"/specialities/retina-uvea"},
{"label":"Diabetic Retina Evaluation","url":"/treatments/diabetic-retina-evaluation"},
{"label":"Retinal Laser Treatment","url":"/treatments/retinal-laser-treatment"},
{"label":"Macular Conditions","url":"/specialities/macular-conditions"},
{"label":"Cornea Care","url":"/specialities/cornea-care"},
{"label":"Keratoconus Evaluation","url":"/treatments/keratoconus-evaluation"},
{"label":"Corneal Surgery","url":"/treatments/corneal-surgery"},
{"label":"Corneal Transplantation","url":"/treatments/corneal-transplantation"}]',2),
(4,'links','Children''s & General Eye Care','[
{"label":"Squint Evaluation & Treatment","url":"/specialities/squint"},
{"label":"Myopia Clinic","url":"/specialities/myopia-clinic"},
{"label":"Vision Therapy","url":"/treatments/vision-therapy"},
{"label":"Comprehensive Eye Examination","url":"/treatments/comprehensive-eye-examination"},
{"label":"Children''s Eye Health","url":"/specialities/childrens-eye-health"},
{"label":"Eye Health Screening","url":"/treatments/eye-health-screening"},
{"label":"All Specialities","url":"/specialities"}]',3);

INSERT INTO mega_menu_columns (nav_item_id, column_type, heading, text, cta_label, cta_url, position) VALUES
(4,'featured','Specialist Eye Care, Close to You','Advanced diagnostics and personalised treatment planning at Shalimar Bagh, Delhi.','Book Appointment','/book-appointment',4);

-- Mega menu: Patient Resources (nav item 6)
INSERT INTO mega_menu_columns (nav_item_id, column_type, title, links_json, position) VALUES
(6,'links','Plan Your Visit','[
{"label":"Patient Journey","url":"/patient-journey"},
{"label":"Book Appointment","url":"/book-appointment"},
{"label":"Insurance & Payment","url":"/insurance-payment"},
{"label":"International Patients","url":"/international-patients"},
{"label":"Contact & Directions","url":"/contact-us"}]',1),
(6,'links','Patient Information','[
{"label":"Patient Education","url":"/patient-education"},
{"label":"FAQs","url":"/faqs"},
{"label":"Patient Safety","url":"/patient-safety"},
{"label":"Videos & Reels","url":"/reels"},
{"label":"Photo Gallery","url":"/gallery"}]',2),
(6,'links','Hospital Updates','[
{"label":"Media & News","url":"/media-news"},
{"label":"Awards","url":"/awards"},
{"label":"Academics","url":"/academics"},
{"label":"Community Initiatives","url":"/community"},
{"label":"Latest Articles","url":"/blog"}]',3);

INSERT INTO mega_menu_columns (nav_item_id, column_type, heading, text, cta_label, cta_url, position) VALUES
(6,'featured','Planning an Eye Consultation?','Call us on 011 4378 4377 or request an appointment online.','Request Appointment','/book-appointment',4);

-- Footer links
INSERT INTO footer_links (column_group, label, url, position) VALUES
('Quick Links','About Us','/about-us',1),
('Quick Links','Our Doctors','/doctors',2),
('Quick Links','Specialities','/specialities',3),
('Quick Links','Technology','/technology',4),
('Quick Links','Blog','/blog',5),
('Quick Links','Contact Us','/contact-us',6),
('Patient Care','Book Appointment','/book-appointment',1),
('Patient Care','Patient Journey','/patient-journey',2),
('Patient Care','Insurance & Payment','/insurance-payment',3),
('Patient Care','FAQs','/faqs',4),
('Patient Care','Patient Safety','/patient-safety',5),
('Resources','Photo Gallery','/gallery',1),
('Resources','Videos & Reels','/reels',2),
('Resources','Media & News','/media-news',3),
('Resources','Privacy Policy','/privacy-policy',4),
('Resources','HTML Sitemap','/sitemap',5);

-- Specialities
INSERT INTO specialities (name, slug, category, short_description, overview, is_featured, status, position) VALUES
('Cataract & IOL','cataract-iol','Cataract & Refractive Care','Evaluation and modern surgical care for cataract with a range of intraocular lens options.','A cataract is clouding of the natural lens of the eye that gradually reduces vision. At Jain Eye Hospital & Laser Centre, cataract is evaluated with a detailed eye examination and appropriate investigations, and surgery is planned according to each patient''s eye condition and visual needs.',1,'published',1),
('LASIK & Refractive Surgery','lasik-refractive','Cataract & Refractive Care','Structured assessment and laser vision correction options for suitable candidates.','Refractive errors such as myopia, hyperopia and astigmatism can often be corrected with laser procedures in suitable candidates. A detailed pre-operative evaluation is essential before any laser vision correction is advised.',1,'published',2),
('Retina & Uvea','retina-uvea','Retina & Cornea','Medical and laser care for retinal conditions including diabetic retinopathy.','The retina is the light-sensitive layer at the back of the eye. Conditions such as diabetic retinopathy, retinal vein occlusion and macular disorders require timely diagnosis and treatment to protect vision.',1,'published',3),
('Macular Conditions','macular-conditions','Retina & Cornea','Evaluation and monitoring of age-related and other macular disorders.','The macula is responsible for central, detailed vision. Macular conditions can cause blurred or distorted central vision and benefit from early detection and regular monitoring.',0,'published',4),
('Cornea Care','cornea-care','Retina & Cornea','Care for corneal infections, keratoconus and other corneal disorders.','The cornea is the clear front window of the eye. Corneal conditions can affect clarity of vision and comfort, and range from infections and injuries to shape disorders such as keratoconus.',1,'published',5),
('Squint Evaluation & Treatment','squint','Children''s & General Eye Care','Assessment and treatment planning for misaligned eyes in children and adults.','Squint (strabismus) is a condition where the eyes do not align properly. Early evaluation is important, especially in children, to support normal visual development.',0,'published',6),
('Myopia Clinic','myopia-clinic','Children''s & General Eye Care','Dedicated care for childhood myopia and guidance on slowing its progression.','Myopia (near-sightedness) is increasingly common in children. Regular monitoring and evidence-based guidance can help manage its progression.',0,'published',7),
('Children''s Eye Health','childrens-eye-health','Children''s & General Eye Care','Comprehensive eye examinations and care tailored for children.','Children may not always complain about vision problems. Regular eye examinations help detect refractive errors, lazy eye (amblyopia), squint and other conditions early.',1,'published',8);

-- Treatments
INSERT INTO treatments (speciality_id, name, slug, short_description, status, position) VALUES
(1,'Cataract Surgery','cataract-surgery','Modern micro-incision cataract surgery with personalised intraocular lens selection.','published',1),
(2,'LASIK Assessment','lasik-assessment','Detailed pre-operative workup to determine suitability for laser vision correction.','published',2),
(2,'Laser Vision Correction','laser-vision-correction','Laser-based procedures to reduce dependence on glasses in suitable candidates.','published',3),
(1,'Intraocular Lens Options','intraocular-lens-options','Guidance on monofocal, toric and other IOL choices based on lifestyle needs.','published',4),
(3,'Diabetic Retina Evaluation','diabetic-retina-evaluation','Structured retinal screening for patients with diabetes.','published',5),
(3,'Retinal Laser Treatment','retinal-laser-treatment','Laser photocoagulation for appropriate retinal conditions.','published',6),
(5,'Keratoconus Evaluation','keratoconus-evaluation','Corneal imaging and assessment for keratoconus and its progression.','published',7),
(5,'Corneal Surgery','corneal-surgery','Surgical care for corneal conditions when medically indicated.','published',8),
(5,'Corneal Transplantation','corneal-transplantation','Transplant options for advanced corneal disease.','published',9),
(6,'Vision Therapy','vision-therapy','Structured therapy programmes for selected binocular vision problems.','published',10),
(8,'Comprehensive Eye Examination','comprehensive-eye-examination','Complete eye health check including vision, pressure and retinal evaluation.','published',11),
(8,'Eye Health Screening','eye-health-screening','Preventive screening packages for early detection of eye disease.','published',12);

-- Doctors (names verified from existing site; complete profiles via admin)
INSERT INTO doctors (name, slug, designation, specialisation, is_featured, status, position) VALUES
('Dr Arun Kumar Jain','dr-arun-kumar-jain','Senior Consultant','Ophthalmology',1,'published',1),
('Dr Rajat Jain','dr-rajat-jain','Consultant','Ophthalmology',1,'published',2),
('Dr Neha Mohan','dr-neha-mohan','Consultant','Ophthalmology',1,'published',3);

-- FAQs (general, medically responsible)
INSERT INTO faqs (question, answer, category, position) VALUES
('How often should I get my eyes checked?','Adults with no symptoms are generally advised to have a comprehensive eye examination every one to two years. People with diabetes, a family history of eye disease, or existing eye conditions may need more frequent check-ups as advised by their eye specialist.','General',1),
('What are the common symptoms of cataract?','Common symptoms include gradual blurring of vision, glare or halos around lights, faded colours and frequent changes in spectacle power. Cataract usually develops slowly and painless. An eye examination is needed to confirm the diagnosis.','Cataract',2),
('Is LASIK suitable for everyone?','No. LASIK suitability depends on factors such as age, stable spectacle power, corneal thickness and overall eye health. A detailed pre-operative assessment is essential before any laser vision correction is recommended.','LASIK',3),
('Why is retinal screening important for diabetic patients?','Diabetes can damage the small blood vessels of the retina (diabetic retinopathy), often without early symptoms. Regular retinal screening helps detect changes early, when treatment is most effective at protecting vision.','Retina',4),
('When should a child have their first eye examination?','A baseline eye examination is generally recommended in early childhood, and earlier if parents notice squinting, sitting very close to screens, frequent eye rubbing or any visible eye misalignment. Early detection supports normal visual development.','Children',5),
('Do you accept insurance or cashless facilities?','Please contact the hospital directly with your insurance or TPA details, and our team will guide you regarding the available payment and reimbursement options.','Billing',6);

-- Blog categories & a review-stage sample article
INSERT INTO blog_categories (id, name, slug, description, position) VALUES
(1,'Eye Care Tips','eye-care-tips','Practical guidance for everyday eye health',1),
(2,'Treatments & Procedures','treatments-procedures','Understanding eye treatments and procedures',2),
(3,'Hospital Updates','hospital-updates','News from Jain Eye Hospital & Laser Centre',3);

INSERT INTO blog_posts (category_id, title, slug, excerpt, content, status) VALUES
(1,'Five Everyday Habits That Support Healthy Vision','everyday-habits-healthy-vision','Simple, everyday habits that can help you look after your eye health.','<p>Good eye health is supported by simple daily habits: taking regular screen breaks, wearing UV-protective sunglasses outdoors, eating a balanced diet rich in leafy greens, avoiding smoking, and keeping chronic conditions like diabetes under control.</p><p>Most importantly, schedule regular comprehensive eye examinations, even when you have no symptoms. Many eye conditions are easier to manage when detected early.</p><p><em>This article is for general education and does not replace a consultation with an eye specialist.</em></p>','review');

-- SEO defaults for key routes
INSERT INTO seo_metadata (route, meta_title, meta_description) VALUES
('/','Eye Hospital in Shalimar Bagh, Delhi | Jain Eye Hospital & Laser Centre','Jain Eye Hospital & Laser Centre offers advanced super-speciality eye care in Shalimar Bagh, Delhi. Book an appointment for cataract, LASIK, retina and cornea care.'),
('/about-us','About Us | Jain Eye Hospital & Laser Centre','Learn about Jain Eye Hospital & Laser Centre, a super-speciality eye hospital in Shalimar Bagh, Delhi - our approach, team and facilities.'),
('/doctors','Our Doctors | Jain Eye Hospital & Laser Centre','Meet the eye specialists at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi and book a consultation.'),
('/specialities','Eye Care Specialities | Jain Eye Hospital & Laser Centre','Explore eye care specialities including cataract, LASIK, retina, cornea, squint and children''s eye care in Shalimar Bagh, Delhi.'),
('/technology','Technology | Jain Eye Hospital & Laser Centre','Diagnostic, laser and surgical technology at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi.'),
('/blog','Eye Care Blog | Jain Eye Hospital & Laser Centre','Eye health articles and hospital updates from Jain Eye Hospital & Laser Centre, Delhi.'),
('/contact-us','Contact Us | Jain Eye Hospital & Laser Centre','Contact Jain Eye Hospital & Laser Centre, AG-152 Shalimar Bagh, Delhi 110088. Call 011 4378 4377.'),
('/book-appointment','Book an Appointment | Jain Eye Hospital & Laser Centre','Request an appointment at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi. Our team will confirm your slot.');

-- Email templates
INSERT INTO email_templates (template_key, subject, body) VALUES
('appointment_new','New Appointment Request - {{reference}}','A new appointment request has been submitted.\n\nReference: {{reference}}\nName: {{name}}\nMobile: {{mobile}}\nEmail: {{email}}\nPreferred Date: {{date}}\nPreferred Time: {{time}}\n\nPlease log in to the admin panel to manage this request.'),
('contact_new','New Website Enquiry - {{subject}}','A new enquiry has been submitted on the website.\n\nName: {{name}}\nEmail: {{email}}\nPhone: {{phone}}\nSubject: {{subject}}\n\nMessage:\n{{message}}');
