FROM php:8.2-apache

# Node + dependencias frontend
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    nodejs npm

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Backend
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Frontend (ESTO ES LO QUE TE FALTA)
RUN npm install
RUN npm run build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
