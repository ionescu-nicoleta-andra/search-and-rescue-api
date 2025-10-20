#!/usr/bin/env bash
set -e

# Wait for DB if necessary (optional)
# until nc -z db 3306; do sleep 1; done

# Run migrations & optimize in container start (optional dev)
php artisan migrate --force || true
php artisan config:cache || true

exec "$@"
