FROM php:8.3-fpm-alpine

# Bibliotecas de runtime + extensões (MySQL, intl, zip, opcache)
RUN apk add --no-cache icu-libs libzip \
 && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev \
 && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli intl zip opcache \
 && apk del .build-deps

COPY php.ini /usr/local/etc/php/conf.d/zz-custom.ini

WORKDIR /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
