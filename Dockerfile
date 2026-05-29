FROM php:8.2-apache

RUN a2enmod rewrite

RUN sed -ri 's!/var/www/html!/app/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!/app/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -ri 's/Listen 80/Listen 8001/g' /etc/apache2/ports.conf \
    && sed -ri 's/:80>/:8001>/g' /etc/apache2/sites-available/*.conf

RUN apt-get update && apt-get install -y \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        unzip \
        curl \
        git \
    && docker-php-ext-install pdo_mysql mbstring gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8001

ENTRYPOINT ["docker-entrypoint.sh"]
