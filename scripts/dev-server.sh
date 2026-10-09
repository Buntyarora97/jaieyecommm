#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
APP_DIR="$ROOT_DIR/jain-eye-hospital"
DATA_DIR="/tmp/jaineye-mariadb-${UID:-1000}"
SOCKET="$DATA_DIR/mysql.sock"
PID_FILE="$DATA_DIR/mysql.pid"
LOG_FILE="$DATA_DIR/mysql.log"
STARTED_DB=0

mkdir -p "$DATA_DIR"

if ! mariadb-admin --protocol=socket --socket="$SOCKET" -uroot ping >/dev/null 2>&1; then
  if [[ ! -d "$DATA_DIR/data/mysql" ]]; then
    mariadb-install-db --datadir="$DATA_DIR/data" --auth-root-authentication-method=normal --skip-test-db --user="$(id -un)" >/dev/null
  fi
  mariadbd \
    --datadir="$DATA_DIR/data" \
    --socket="$SOCKET" \
    --pid-file="$PID_FILE" \
    --log-error="$LOG_FILE" \
    --user="$(id -un)" \
    --bind-address=127.0.0.1 \
    --port=3306 \
    --skip-name-resolve &
  DB_PID=$!
  STARTED_DB=1
  for attempt in $(seq 1 60); do
    if mariadb-admin --protocol=socket --socket="$SOCKET" -uroot ping >/dev/null 2>&1; then break; fi
    if ! kill -0 "$DB_PID" 2>/dev/null; then cat "$LOG_FILE"; exit 1; fi
    sleep 1
  done
  mariadb-admin --protocol=socket --socket="$SOCKET" -uroot ping >/dev/null
fi

stop_local_db() {
  if [[ "$STARTED_DB" -eq 1 ]]; then
    mariadb-admin --protocol=socket --socket="$SOCKET" -uroot shutdown >/dev/null 2>&1 || true
  fi
}
trap stop_local_db EXIT INT TERM

mariadb --protocol=socket --socket="$SOCKET" -uroot <<'SQL'
CREATE DATABASE IF NOT EXISTS jaineye_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'jaineye_dev'@'127.0.0.1' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON jaineye_dev.* TO 'jaineye_dev'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

TABLE_COUNT="$(mariadb --protocol=socket --socket="$SOCKET" -uroot -Nse \
  "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='jaineye_dev'")"
if [[ "$TABLE_COUNT" -eq 0 ]]; then
  mariadb --default-character-set=utf8mb4 --protocol=socket --socket="$SOCKET" -uroot jaineye_dev < "$APP_DIR/database/schema.sql"
fi

REEL_URL_COLUMN_COUNT="$(mariadb --protocol=socket --socket="$SOCKET" -uroot -Nse \
  "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='jaineye_dev' AND table_name='reels' AND column_name='video_url'")"
if [[ "$REEL_URL_COLUMN_COUNT" -eq 0 ]]; then
  mariadb --protocol=socket --socket="$SOCKET" -uroot jaineye_dev \
    -e "ALTER TABLE reels ADD COLUMN video_url VARCHAR(500) DEFAULT NULL AFTER video_path"
fi

SEO_KEYWORDS_COLUMN_COUNT="$(mariadb --protocol=socket --socket="$SOCKET" -uroot -Nse \
  "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='jaineye_dev' AND table_name='seo_metadata' AND column_name='meta_keywords'")"
if [[ "$SEO_KEYWORDS_COLUMN_COUNT" -eq 0 ]]; then
  mariadb --protocol=socket --socket="$SOCKET" -uroot jaineye_dev < "$APP_DIR/database/migrations/20261009_add_seo_keywords.sql"
fi

mariadb --protocol=socket --socket="$SOCKET" -uroot jaineye_dev <<'SQL'
UPDATE doctors SET photo=NULL
  WHERE slug='dr-rajat-jain' AND photo='assets/img/rajat-jain-3.webp';
UPDATE gallery_items SET is_active=0
  WHERE title='Dr Rajat Jain' AND image='assets/img/rajat-jain-3.webp';
SQL

cd "$APP_DIR"
export DB_HOST=127.0.0.1 DB_NAME=jaineye_dev DB_USER=jaineye_dev DB_PASS=''
export APP_ENV=development SITE_URL=http://127.0.0.1:5000 MAIL_ENABLED=false
php -S 0.0.0.0:5000 -t "$APP_DIR" "$APP_DIR/index.php" &
PHP_PID=$!
shutdown() {
  kill "$PHP_PID" 2>/dev/null || true
  wait "$PHP_PID" 2>/dev/null || true
  stop_local_db
}
trap shutdown EXIT INT TERM
wait "$PHP_PID"
