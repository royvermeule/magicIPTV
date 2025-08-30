FROM php:8.4-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy vhost
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install PDO extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set timezone
ARG TZ=Europe/Amsterdam
RUN echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini

# Copy code (prod fallback – dev can override with a volume)
COPY . /var/www/html

# Ensure Doctrine proxy dir exists and is writable
RUN mkdir -p /var/www/html/var/doctrine/proxies \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var
