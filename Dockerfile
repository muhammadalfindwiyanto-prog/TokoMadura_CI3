FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN rm -f /etc/apache2/mods-enabled/mpm_event.load
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]