# Dockerfile
FROM php:8.4-apache

# Enable mod_rewrite for .htaccess rewrites
RUN a2enmod rewrite

# Use our vhost that points DocumentRoot to /var/www/html/public
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# (Optional) install PHP extensions
# RUN docker-php-ext-install pdo_mysql
