# Laravel uchun Dockerfile

# 1. Rasmni tanlash
FROM php:8.1-fpm

# 2. Kerakli paketlarni o'rnatish
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# 3. PHP kutubxonalarini o'rnatish
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# 4. Composer o'rnatish
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Laravel ilovasini konteynerga joylashtirish
WORKDIR /var/www

# 6. Host-dan loyiha fayllarini konteynerga nusxalash
COPY . /var/www

# 7. Kerakli kataloglarga ruxsat berish
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 8. Composer-ni ishga tushirish
RUN composer install --no-scripts --no-autoloader

EXPOSE 9000
CMD ["php-fpm"]
