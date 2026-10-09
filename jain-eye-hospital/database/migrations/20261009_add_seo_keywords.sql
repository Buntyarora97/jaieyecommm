-- Existing installations: back up the database, then run this migration once.
ALTER TABLE seo_metadata
  ADD COLUMN meta_keywords VARCHAR(500) DEFAULT NULL AFTER meta_description;

-- Refresh only the original seeded values; retain any custom metadata entered in the CMS.
UPDATE seo_metadata
SET meta_title='Eye Hospital in Shalimar Bagh, Delhi | Jain Eye Hospital',
    meta_description='Jain Eye Hospital in Shalimar Bagh, Delhi. Explore eye-care services, doctor profiles and appointment information for cataract, LASIK, retina and cornea care.'
WHERE route='/' AND meta_title='Eye Hospital in Shalimar Bagh, Delhi | Jain Eye Hospital & Laser Centre'
  AND meta_description='Jain Eye Hospital & Laser Centre offers advanced super-speciality eye care in Shalimar Bagh, Delhi. Book an appointment for cataract, LASIK, retina and cornea care.';

UPDATE seo_metadata
SET meta_title='About Jain Eye Hospital & Laser Centre | Delhi',
    meta_description='Learn about Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi, including our team, patient-care approach and available eye-care services.'
WHERE route='/about-us' AND meta_title='About Us | Jain Eye Hospital & Laser Centre'
  AND meta_description='Learn about Jain Eye Hospital & Laser Centre, a super-speciality eye hospital in Shalimar Bagh, Delhi - our approach, team and facilities.';

UPDATE seo_metadata
SET meta_title='Eye Doctors in Shalimar Bagh, Delhi | Jain Eye Hospital',
    meta_description='Meet the doctors listed at Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi. View published profiles and request appointment information.'
WHERE route='/doctors' AND meta_title='Our Doctors | Jain Eye Hospital & Laser Centre'
  AND meta_description='Meet the eye specialists at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi and book a consultation.';

UPDATE seo_metadata
SET meta_title='Eye Care Specialities in Delhi | Jain Eye Hospital',
    meta_description='Explore cataract, LASIK, retina, cornea, squint, myopia and children''s eye-care information from Jain Eye Hospital, Shalimar Bagh, Delhi.'
WHERE route='/specialities' AND meta_title='Eye Care Specialities | Jain Eye Hospital & Laser Centre'
  AND meta_description='Explore eye care specialities including cataract, LASIK, retina, cornea, squint and children''s eye care in Shalimar Bagh, Delhi.';

UPDATE seo_metadata
SET meta_title='Eye-Care Technology & Clinical Spaces | Jain Eye Hospital',
    meta_description='Explore hospital-published information about diagnostic, laser and surgical technology at Jain Eye Hospital in Shalimar Bagh, Delhi.'
WHERE route='/technology' AND meta_title='Technology | Jain Eye Hospital & Laser Centre'
  AND meta_description='Diagnostic, laser and surgical technology at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi.';

UPDATE seo_metadata
SET meta_title='Eye Care Articles & Hospital Updates | Jain Eye Hospital',
    meta_description='Read eye-health articles and hospital updates from Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi.'
WHERE route='/blog' AND meta_title='Eye Care Blog | Jain Eye Hospital & Laser Centre'
  AND meta_description='Eye health articles and hospital updates from Jain Eye Hospital & Laser Centre, Delhi.';

UPDATE seo_metadata
SET meta_title='Contact Jain Eye Hospital | Shalimar Bagh, Delhi',
    meta_description='Contact Jain Eye Hospital & Laser Centre at AG-152, Shalimar Bagh, Delhi 110088, or call 011 4378 4377 for information.'
WHERE route='/contact-us' AND meta_title='Contact Us | Jain Eye Hospital & Laser Centre'
  AND meta_description='Contact Jain Eye Hospital & Laser Centre, AG-152 Shalimar Bagh, Delhi 110088. Call 011 4378 4377.';

UPDATE seo_metadata
SET meta_title='Book an Eye Appointment | Jain Eye Hospital Delhi',
    meta_description='Request an appointment at Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi. The team will follow up about availability.'
WHERE route='/book-appointment' AND meta_title='Book an Appointment | Jain Eye Hospital & Laser Centre'
  AND meta_description='Request an appointment at Jain Eye Hospital & Laser Centre, Shalimar Bagh, Delhi. Our team will confirm your slot.';

INSERT INTO seo_metadata (route, meta_title, meta_description, meta_keywords, robots) VALUES
('/','Eye Hospital in Shalimar Bagh, Delhi | Jain Eye Hospital','Jain Eye Hospital in Shalimar Bagh, Delhi. Explore eye-care services, doctor profiles and appointment information for cataract, LASIK, retina and cornea care.','eye hospital Shalimar Bagh, eye hospital Delhi, eye care Delhi, cataract care, LASIK, retina care, cornea care, Jain Eye Hospital','index,follow'),
('/about-us','About Jain Eye Hospital & Laser Centre | Delhi','Learn about Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi, including our team, patient-care approach and available eye-care services.','about Jain Eye Hospital, eye hospital Shalimar Bagh, eye care Delhi, hospital team Delhi','index,follow'),
('/doctors','Eye Doctors in Shalimar Bagh, Delhi | Jain Eye Hospital','Meet the doctors listed at Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi. View published profiles and request appointment information.','eye doctors Delhi, ophthalmologists Shalimar Bagh, Jain Eye Hospital doctors, eye specialist Delhi','index,follow'),
('/specialities','Eye Care Specialities in Delhi | Jain Eye Hospital','Explore cataract, LASIK, retina, cornea, squint, myopia and children''s eye-care information from Jain Eye Hospital, Shalimar Bagh, Delhi.','eye care specialities Delhi, cataract care, LASIK information, retina care, cornea care, children eye health','index,follow'),
('/treatments','Eye Treatments & Procedures | Jain Eye Hospital Delhi','Browse published information about eye treatments at Jain Eye Hospital, Shalimar Bagh, Delhi, and contact the care team with questions.','eye treatments Delhi, cataract surgery information, LASIK assessment, retinal care, corneal procedures','index,follow'),
('/technology','Eye-Care Technology & Clinical Spaces | Jain Eye Hospital','Explore hospital-published information about diagnostic, laser and surgical technology at Jain Eye Hospital in Shalimar Bagh, Delhi.','eye hospital technology Delhi, ophthalmic diagnostics, eye-care equipment information, Jain Eye Hospital facilities','index,follow'),
('/patient-journey','Your Patient Visit | Jain Eye Hospital, Delhi','Learn what to expect when arranging a visit to Jain Eye Hospital in Shalimar Bagh, Delhi, from appointment request through follow-up.','eye hospital visit Delhi, patient journey, eye appointment preparation, Jain Eye Hospital','index,follow'),
('/patient-safety','Patient Safety Information | Jain Eye Hospital Delhi','Read patient-safety questions and preparation guidance for eye-care visits at Jain Eye Hospital, Shalimar Bagh, Delhi.','patient safety eye care, eye consultation preparation, patient questions, eye hospital Delhi','index,follow'),
('/insurance-payment','Insurance & Payment Information | Jain Eye Hospital','Ask Jain Eye Hospital in Shalimar Bagh, Delhi about insurance, payment methods and estimates before your appointment.','eye hospital insurance Delhi, eye-care payment information, appointment estimates, Jain Eye Hospital','index,follow'),
('/international-patients','International Patients | Jain Eye Hospital Delhi','Contact Jain Eye Hospital in Delhi to ask about appointments, available services and visit information before planning travel.','international patient eye care Delhi, eye hospital Delhi visit planning, Jain Eye Hospital appointments','index,follow'),
('/patient-education','Patient Education & Eye Health | Jain Eye Hospital','Read patient education articles and practical eye-health information published by Jain Eye Hospital & Laser Centre in Delhi.','eye health education, patient education Delhi, eye care information, Jain Eye Hospital articles','index,follow'),
('/faqs','Eye Care FAQs | Jain Eye Hospital Delhi','Find answers to common questions about appointments and eye care at Jain Eye Hospital in Shalimar Bagh, Delhi.','eye care FAQs Delhi, eye appointment questions, Jain Eye Hospital information','index,follow'),
('/blog','Eye Care Articles & Hospital Updates | Jain Eye Hospital','Read eye-health articles and hospital updates from Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi.','eye health articles Delhi, eye care blog, hospital updates, Jain Eye Hospital','index,follow'),
('/gallery','Hospital Photo Gallery | Jain Eye Hospital Delhi','View hospital-published photos of Jain Eye Hospital, including clinical spaces, technology, events and community activities.','Jain Eye Hospital photos, eye hospital gallery Delhi, clinical spaces, community activities','index,follow'),
('/reels','Eye-Care Videos | Jain Eye Hospital Delhi','Watch eye-care videos and hospital updates published by Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi.','eye care videos Delhi, ophthalmology videos, Jain Eye Hospital reels, eye health education','index,follow'),
('/awards','Awards & Recognition | Jain Eye Hospital Delhi','View awards and recognition records published by Jain Eye Hospital & Laser Centre in Delhi.','Jain Eye Hospital awards, hospital recognition Delhi, published awards','index,follow'),
('/academics','Ophthalmology Academics & Training | Jain Eye Hospital','Explore academic activities and training information published by Jain Eye Hospital & Laser Centre in Delhi.','ophthalmology academics Delhi, eye-care training information, Jain Eye Hospital academics','index,follow'),
('/community','Community Eye-Care Initiatives | Jain Eye Hospital','Learn about community initiatives published by Jain Eye Hospital & Laser Centre in Delhi.','community eye care Delhi, eye health initiatives, Jain Eye Hospital community','index,follow'),
('/media-news','Media & News | Jain Eye Hospital & Laser Centre','Read news and media updates published by Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi.','Jain Eye Hospital news, eye hospital media Delhi, hospital updates','index,follow'),
('/contact-us','Contact Jain Eye Hospital | Shalimar Bagh, Delhi','Contact Jain Eye Hospital & Laser Centre at AG-152, Shalimar Bagh, Delhi 110088, or call 011 4378 4377 for information.','contact Jain Eye Hospital, eye hospital Shalimar Bagh address, eye hospital Delhi phone','index,follow'),
('/book-appointment','Book an Eye Appointment | Jain Eye Hospital Delhi','Request an appointment at Jain Eye Hospital & Laser Centre in Shalimar Bagh, Delhi. The team will follow up about availability.','eye appointment Delhi, book eye doctor appointment, Jain Eye Hospital appointment, Shalimar Bagh eye care','index,follow'),
('/thank-you','Thank You | Jain Eye Hospital','Your request has been received by Jain Eye Hospital & Laser Centre.','appointment request confirmation, Jain Eye Hospital','noindex,nofollow'),
('/sitemap','Website Sitemap | Jain Eye Hospital','Browse the public pages of Jain Eye Hospital & Laser Centre, including doctors, eye-care services and patient information.','Jain Eye Hospital sitemap, website pages, eye hospital Delhi','index,follow'),
('/privacy-policy','Privacy Policy | Jain Eye Hospital','Read how Jain Eye Hospital & Laser Centre handles information submitted through this website.','Jain Eye Hospital privacy policy, website privacy Delhi','index,follow'),
('/terms-and-conditions','Terms & Conditions | Jain Eye Hospital','Review the terms for using the Jain Eye Hospital & Laser Centre website and its enquiry forms.','Jain Eye Hospital terms, website terms and conditions','index,follow'),
('/medical-disclaimer','Medical Disclaimer | Jain Eye Hospital','Read the limits of general medical information published on the Jain Eye Hospital & Laser Centre website.','Jain Eye Hospital medical disclaimer, general eye-care information','index,follow'),
('/accessibility','Website Accessibility | Jain Eye Hospital','Read accessibility information and contact details for the Jain Eye Hospital & Laser Centre website.','Jain Eye Hospital website accessibility, accessibility contact','index,follow')
ON DUPLICATE KEY UPDATE
  meta_keywords = COALESCE(NULLIF(seo_metadata.meta_keywords, ''), VALUES(meta_keywords));
