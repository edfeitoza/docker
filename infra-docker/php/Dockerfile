FROM php:8.3-fpm-alpine

# Bibliotecas de runtime + extensões (MySQL, intl, zip, opcache)
RUN apk add --no-cache icu-libs libzip \
 && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev \
 && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli intl zip opcache \
 && apk del .build-deps

# Diretórios persistentes (montados como volumes no compose)
# Criados com dono www-data para o volume herdar a permissão correta
RUN mkdir -p /var/lib/php/sessions /var/log/php /var/www/storage \
 && chown -R www-data:www-data /var/lib/php/sessions /var/log/php /var/www/storage

COPY php.ini /usr/local/etc/php/conf.d/zz-custom.ini

WORKDIR /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
