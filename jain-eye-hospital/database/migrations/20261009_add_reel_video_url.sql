-- Existing installations only: back up the database, then run this once
-- to support official external reel links in addition to uploaded MP4 files.
ALTER TABLE reels
  ADD COLUMN video_url VARCHAR(500) DEFAULT NULL AFTER video_path;
