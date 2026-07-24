FROM php:8.2-fpm-bookworm

ENV PORT=8080
WORKDIR /var/www/html

# PHP-FPM tetap berjalan di port internal 9000.
# Nginx menerima trafik HTTP Railway pada 0.0.0.0:$PORT.
RUN apt-get update \
    && apt-get install -y --no-install-recommends nginx gettext-base curl \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf

# Salin seluruh project CI3 ke web root.
COPY . /var/www/html/

# Atur folder dan permission.
RUN mkdir -p /var/www/html/application/cache \
             /var/www/html/application/logs \
             /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 /var/www/html/application/cache \
                    /var/www/html/application/logs \
                    /var/www/html/uploads

# Pengaturan PHP.
RUN printf '%s\n' \
    'upload_max_filesize=20M' \
    'post_max_size=25M' \
    'memory_limit=256M' \
    'max_execution_time=120' \
    'date.timezone=Asia/Jakarta' \
    > /usr/local/etc/php/conf.d/toko-madura.ini

# Konfigurasi Nginx dibuat langsung di Dockerfile.
RUN printf '%s\n' \
'server {' \
'    listen 0.0.0.0:${PORT};' \
'    listen [::]:${PORT};' \
'    server_name _;' \
'' \
'    root /var/www/html;' \
'    index index.php index.html;' \
'    client_max_body_size 25M;' \
'' \
'    access_log /dev/stdout;' \
'    error_log /dev/stderr warn;' \
'' \
'    location / {' \
'        try_files $uri $uri/ /index.php?$query_string;' \
'    }' \
'' \
'    location ^~ /application/ { deny all; }' \
'    location ^~ /system/ { deny all; }' \
'    location ^~ /database/ { deny all; }' \
'' \
'    location ~ \.php$ {' \
'        try_files $uri =404;' \
'        include fastcgi_params;' \
'        fastcgi_pass 127.0.0.1:9000;' \
'        fastcgi_index index.php;' \
'        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
'        fastcgi_param HTTP_AUTHORIZATION $http_authorization;' \
'        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;' \
'        fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;' \
'    }' \
'' \
'    location ~ /\.(?!well-known).* {' \
'        deny all;' \
'    }' \
'}' \
> /etc/nginx/conf.d/default.conf.template

EXPOSE 8080

STOPSIGNAL SIGQUIT

CMD ["/bin/sh", "-c", "envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && php-fpm -D && exec nginx -g 'daemon off;'"]