FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libapache2-mod-php

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]