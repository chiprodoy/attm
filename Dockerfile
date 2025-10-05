FROM php:8.2-fpm

# ======================================================
#  Install dependency dasar PHP + ODBC + build tools
# ======================================================
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    build-essential autoconf pkg-config unixodbc unixodbc-dev mdbtools odbcinst odbc-mdbtools \
    && docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr \
    && docker-php-ext-install pdo_odbc pdo_mysql mbstring exif pcntl bcmath gd zip

# ======================================================
#  Install Composer
# ======================================================
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# ======================================================
#  Set working directory
# ======================================================
WORKDIR /var/www

# Copy seluruh backend Laravel ke container
COPY backend/ ./

# Install dependency Laravel
RUN composer install --optimize-autoloader --no-dev

# ======================================================
#  NodeJS untuk build React/Inertia
# ======================================================
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build

# ======================================================
#  Permission dan CMD
# ======================================================
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
