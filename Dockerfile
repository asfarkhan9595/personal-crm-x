# Stage 1: Build Node / Vite assets
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci || npm install
COPY . .
RUN npm run build

# Stage 2: PHP / Nginx production runtime
FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

# Copy compiled frontend assets from Node build stage
COPY --from=node_builder /app/public/build ./public/build

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy custom Nginx configuration for Laravel routing
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default.conf

# Ensure storage & bootstrap permissions
RUN chmod -R 775 storage bootstrap/cache

# Environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Run storage symlink, migrations, and seed initial data, then start Laravel/Nginx
CMD ["sh", "-c", "php artisan storage:link --force; php artisan migrate --force; php artisan db:seed --force; /start.sh"]