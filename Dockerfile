FROM php:8.2-fpm-bookworm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends nginx \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

RUN rm -f /etc/nginx/sites-enabled/default \
    && mkdir -p /var/www/html/uploads/barang /var/www/html/application/cache /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 8080

CMD ["sh", "-c", "sed -i \"s/__PORT__/${PORT:-8080}/g\" /etc/nginx/conf.d/default.conf && php-fpm -D && exec nginx -g 'daemon off;'"]
