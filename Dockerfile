FROM php:8.2-fpm-bookworm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y nginx gettext-base \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*


COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html


RUN mkdir -p /etc/nginx/conf.d


RUN printf 'server {\n\
listen 0.0.0.0:$PORT;\n\
server_name _;\n\
root /var/www/html;\n\
index index.php index.html;\n\
\n\
location / {\n\
try_files $uri $uri/ /index.php?$query_string;\n\
}\n\
\n\
location ~ \\.php$ {\n\
include fastcgi_params;\n\
fastcgi_pass 127.0.0.1:9000;\n\
fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
}\n\
}' > /etc/nginx/conf.d/default.conf


EXPOSE 8080


CMD php-fpm -D && nginx -g 'daemon off;'