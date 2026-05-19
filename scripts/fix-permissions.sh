#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────
# SIMALUN — Fix folder permissions for Laravel runtime.
# Usage:
#   bash scripts/fix-permissions.sh [web_user] [web_group]
# Defaults to www-data:www-data (Debian/Ubuntu Nginx/Apache).
# ─────────────────────────────────────────────────────────────
set -euo pipefail

WEB_USER="${1:-www-data}"
WEB_GROUP="${2:-www-data}"

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_ROOT"

echo "→ Project: $PROJECT_ROOT"
echo "→ Owning user/group: $WEB_USER:$WEB_GROUP"

# Pastikan folder ada
mkdir -p storage/app/public storage/framework/{cache,sessions,testing,views} storage/logs
mkdir -p bootstrap/cache

# Ownership
if [ "$(id -u)" -eq 0 ]; then
    chown -R "$WEB_USER:$WEB_GROUP" storage bootstrap/cache
else
    sudo chown -R "$WEB_USER:$WEB_GROUP" storage bootstrap/cache
fi

# Permissions: 775 untuk folder, 664 untuk file
find storage bootstrap/cache -type d -exec chmod 775 {} +
find storage bootstrap/cache -type f -exec chmod 664 {} +

# Pastikan symlink storage→public ada
if [ ! -L public/storage ]; then
    echo "→ public/storage symlink hilang, menjalankan storage:link..."
    php artisan storage:link --force
else
    echo "→ public/storage symlink OK."
fi

echo "✓ Permission fix selesai."
