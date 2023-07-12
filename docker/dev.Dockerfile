FROM php:8.1-fpm-alpine3.15

ARG APP_ENV
RUN apk add --no-cache git \
   && apk add --virtual .build-deps g++ autoconf icu-dev make  \
   && pecl install xdebug-3.1.3 \
   && docker-php-ext-enable xdebug \
   && apk del .build-deps g++ autoconf icu-dev make  \
   && rm -rf /var/cache/apk/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY docker/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/

RUN install-php-extensions zip bcmath @composer; \
    rm /usr/local/bin/install-php-extensions;

WORKDIR /app
