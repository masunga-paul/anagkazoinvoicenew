#!/usr/bin/env bash
set -e

echo "==> Starting Anagkazo Autoparts ERP Production Container..."

# If SQLite is used and database path doesn't exist, create it
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_PATH")"
    if [ ! -f "$DB_PATH" ]; then
        echo "==> Initializing SQLite database at $DB_PATH..."
        touch "$DB_PATH"
        chmod 664 "$DB_PATH"
        chown www-data:www-data "$DB_PATH"
    fi
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symbolic link
php artisan storage:link --force || true

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force --isolated || true

# Seed database if users table is empty
echo "==> Checking if initial database seeding is required..."
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "==> Seeding essential initial credentials and payment methods..."
    php artisan db:seed --force || true
fi

# Cache configurations, routes, and blade views for high-performance production execution
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Optimizing Laravel cache for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Start PHP-FPM daemon
echo "==> Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "==> Starting Nginx server..."
exec nginx -g "daemon off;"
