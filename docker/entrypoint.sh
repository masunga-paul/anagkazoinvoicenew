#!/usr/bin/env bash
set -e

echo "==> Starting Anagkazo Autoparts ERP Production Container..."

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Fallback to native Railway MySQL environment variables if DB_HOST or DB_DATABASE is blank
if [ -z "${DB_HOST}" ] && [ -n "${MYSQLHOST}" ]; then
    export DB_HOST="${MYSQLHOST}"
fi
if [ -z "${DB_PORT}" ] && [ -n "${MYSQLPORT}" ]; then
    export DB_PORT="${MYSQLPORT}"
fi
if [ -z "${DB_DATABASE}" ] && [ -n "${MYSQLDATABASE}" ]; then
    export DB_DATABASE="${MYSQLDATABASE}"
fi
if [ -z "${DB_USERNAME}" ] && [ -n "${MYSQLUSER}" ]; then
    export DB_USERNAME="${MYSQLUSER}"
fi
if [ -z "${DB_PASSWORD}" ] && [ -n "${MYSQLPASSWORD}" ]; then
    export DB_PASSWORD="${MYSQLPASSWORD}"
fi
if [ -z "${DB_URL}" ] && [ -n "${MYSQL_URL}" ]; then
    export DB_URL="${MYSQL_URL}"
fi

# Ensure APP_KEY exists
if [ -z "${APP_KEY}" ]; then
    echo "==> APP_KEY is not set. Generating application encryption key..."
    export APP_KEY="$(php artisan key:generate --show)"
fi

echo "==> Database Mode: Connection=${DB_CONNECTION:-sqlite}, Host=${DB_HOST:-none}, Database=${DB_DATABASE:-none}"

# If a volume was mounted over /var/www/html/database, restore migrations and seeders
if [ ! -d "/var/www/html/database/migrations" ] && [ -d "/var/www/html/database_src/migrations" ]; then
    echo "==> Restoring migration and seeder definitions to mounted database volume..."
    cp -rn /var/www/html/database_src/* /var/www/html/database/ || true
fi

# If SQLite is used, ensure database file and containing directory have full write permissions
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    DB_DIR="$(dirname "$DB_PATH")"
    mkdir -p "$DB_DIR"
    chmod 777 "$DB_DIR"
    chown -R www-data:www-data "$DB_DIR"
    if [ ! -f "$DB_PATH" ]; then
        echo "==> Initializing SQLite database file at $DB_PATH..."
        touch "$DB_PATH"
    fi
    chmod 666 "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
fi

# Clear old configuration cache before migrating
php artisan config:clear || true

# If MySQL is configured, wait up to 20 seconds for connection readiness
if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "==> Verifying MySQL database connection readiness..."
    for i in $(seq 1 10); do
        if php artisan db:show > /dev/null 2>&1; then
            echo "==> Database is reachable!"
            break
        fi
        echo "==> Waiting for database to accept connections ($i/10)..."
        sleep 2
    done
fi

# Run database migrations with resilience
echo "==> Running database migrations..."
php artisan migrate --force || echo "==> [Warning] Migration exited with error, continuing container boot..."

# Seed database if essential admin account does not exist
echo "==> Running database seeder for initial roles and credentials..."
php artisan db:seed --force || echo "==> [Warning] Seeder exited with error, continuing container boot..."

# Create storage symbolic link and publish livewire assets
php artisan storage:link --force || true
php artisan livewire:publish --assets || true

# Cache configurations, routes, and blade views for high-performance production execution
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "==> Optimizing Laravel cache for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Configure Nginx port dynamically if PORT is provided by Railway
TARGET_PORT="${PORT:-80}"
echo "==> Configuring Nginx (listening on 80, 8080, and ${TARGET_PORT})..."
if [ "${TARGET_PORT}" != "80" ] && [ "${TARGET_PORT}" != "8080" ]; then
    sed -i "s/listen 80 default_server;/listen 80 default_server;\n    listen ${TARGET_PORT};\n    listen [::]:${TARGET_PORT};/g" /etc/nginx/http.d/default.conf
fi

# Test Nginx configuration
nginx -t

# Start PHP-FPM daemon
echo "==> Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "==> Starting Nginx server..."
exec nginx -g "daemon off;"
