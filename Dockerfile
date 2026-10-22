FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY Docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html
