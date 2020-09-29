# https://hub.docker.com/_/php/
# PHP7-FPM
FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    autoconf \
    curl \
    g++ \
    gcc \
    git \
    libtool \
    make \
    tar \
    freetype-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    pcre-dev \
    zip \
    unzip \
    wget

RUN mkdir /var/log/php

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \

    # Finally, install php compiled extensions
    && docker-php-ext-install \
        gd \
        mysqli \
        pdo \
        pdo_mysql \
        tokenizer \
        opcache

RUN apk add --no-cache \
    oniguruma-dev \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mbstring \
    && rm -rf /tmp/*

# Intl
RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev
RUN apk add --no-cache --update libmemcached-libs zlib
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS \
    && pecl install memcached \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/20_memcached.ini \
    && rm -rf /usr/share/php7 \
    && rm -rf /tmp/* \