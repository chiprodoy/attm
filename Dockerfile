FROM php:8.2-fpm

# ======================================================
#  Install dependency dasar PHP + ODBC + build tools
# ======================================================
RUN apt-get update && apt-get install -y \
    git supervisor curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev tzdata \
    build-essential autoconf pkg-config unixodbc unixodbc-dev mdbtools odbcinst odbc-mdbtools cron \
    && docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr \
    && docker-php-ext-install pdo_odbc pdo_mysql mbstring exif pcntl bcmath gd zip

# Set timezone ke Asia/Jakarta
ENV TZ=Asia/Jakarta
RUN echo "Asia/Jakarta" > /etc/timezone \
    && ln -snf /usr/share/zoneinfo/Asia/Jakarta /etc/localtime

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
    && npm install && npm run build \
    && npm cache clean --force

# ======================================================
#  Permission dan CMD
# ======================================================
RUN mkdir -p /run/php /var/log/supervisor /var/www/storage/logs \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache
    

# Copy konfigurasi Supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Gunakan supervisord untuk menjalankan php-fpm + queue + cron bersamaan
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
