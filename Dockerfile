# ==========================================
# STAGE 1: Composer Dependencies
# ==========================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --optimize-autoloader \
    --no-scripts

# ==========================================
# STAGE 2: Frontend Asset Compilation (Vite)
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

# Copy full application code including vendor directory with Livewire Flux CSS
COPY . .
COPY --from=vendor /app/vendor ./vendor

RUN npm run build

# ==========================================
# STAGE 3: PHP 8.4 + Nginx Production Runtime
# ==========================================
FROM php:8.4-fpm-alpine AS backend

# Install system utilities and build dependencies
RUN apk update && apk add --no-cache \
    nginx \
    bash \
    curl \
    git \
    zip \
    unzip \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype \
    freetype-dev \
    libzip \
    libzip-dev \
    icu-libs \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    sqlite-libs

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        pdo_pgsql \
        bcmath \
        zip \
        gd \
        intl \
        mbstring \
        opcache \
        exif

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy application source code
COPY . .

# Copy vendor packages from composer stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy compiled frontend assets from Node stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Finish composer autoloader dump and package discovery
RUN composer dump-autoload --optimize --no-dev --ignore-platform-reqs

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Environment variables defaults
ENV PORT=80
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
