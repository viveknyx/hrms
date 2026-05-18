#!/bin/sh
set -eu

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/framework/hrms-views \
    storage/logs \
    bootstrap/cache

touch storage/logs/laravel.log
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Run migrations only when explicitly enabled. A remote database networking
# issue should not prevent the web container from starting on Render.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    if ! php artisan migrate --force; then
        echo "WARNING: php artisan migrate failed. Continuing startup because FAIL_ON_MIGRATION_ERROR is not true." >&2

        if [ "${FAIL_ON_MIGRATION_ERROR:-false}" = "true" ]; then
            exit 1
        fi
    fi
fi

# Clear and cache config for production
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
