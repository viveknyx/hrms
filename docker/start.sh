#!/usr/bin/env sh
set -eu

PORT="${PORT:-10000}"

php artisan config:clear
php artisan route:clear
php artisan view:clear

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${CACHE_LARAVEL:-true}" = "true" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

php artisan serve --host=0.0.0.0 --port="$PORT"
