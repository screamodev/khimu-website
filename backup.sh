#!/usr/bin/env bash
set -euo pipefail

# підтягуємо змінні з .env
set -a
. /home/screamo_dev/khimu-website/.env
set +a


set -euo pipefail

# Каталог для бекапів
BACKUP_DIR="/home/screamo_dev/khimu-website/database/backups"
mkdir -p "$BACKUP_DIR"

# Дата у форматі YYYY-MM-DD_HHMM
TS=$(date +%F_%H%M)

# Ім'я контейнера БД
DB_CONTAINER="khimu-website-db-1"

# Креденшали (збігаються з вашим docker-compose.yml)
DB_NAME="wpdb"
DB_USER="wpuser"
DB_PASS="${MARIADB_PASSWORD}"   # можна жорстко вказати, якщо немає ENV
DB_ROOT_PASS="${MARIADB_ROOT_PASSWORD}"

# Створюємо дамп
docker exec "$DB_CONTAINER" \
  mariadb-dump -u"$DB_USER" -p"$DB_PASS" --single-transaction --routines --events "$DB_NAME" \
  > "$BACKUP_DIR/${DB_NAME}_${TS}.sql"

# Залишаємо тільки останні 8 бекапів (~місяць, якщо 2 рази на тиждень)
ls -1t "$BACKUP_DIR"/${DB_NAME}_*.sql | tail -n +9 | xargs -r rm --
