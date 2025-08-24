# Dockerfile
FROM php:8.4-apache

# Enable mod_rewrite for .htaccess rewrites
RUN a2enmod rewrite

# Use our vhost that points DocumentRoot to /var/www/html/public
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install PDO MySQL and mysqli extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set timezone for PHP (match container's TZ env var)
ARG TZ=Europe/Amsterdam
RUN echo "date.timezone=${TZ}" > /usr/local/etc/php/conf.d/timezone.ini
