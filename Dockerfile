FROM php:8.2-fpm-bookworm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y nginx \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*


COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html


RUN rm -f /etc/nginx/sites-enabled/default


COPY nginx.conf /etc/nginx/conf.d/default.conf


EXPOSE 8080


CMD php-fpm -D && nginx -g "daemon off;"