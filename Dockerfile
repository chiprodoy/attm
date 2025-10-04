FROM php:8.2-fpm

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Install NodeJS untuk build frontend inertia/react
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set working dir di dalam container
WORKDIR /var/www

# Copy source Laravel (folder backend ke /var/www)
COPY backend/ ./

# Install dependencies Laravel
RUN composer install --optimize-autoloader --no-dev

# Build frontend inertia/react
RUN npm install && npm run build

# Set permission storage & cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
