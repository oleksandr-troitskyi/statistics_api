# https://hub.docker.com/_/php/
# PHP7-CLI
FROM php:7.4-cli

WORKDIR /www

# Configure library constraints
ENV VERSION_COMPOSER_ASSET_PLUGIN=^1.2.0 \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.0 \
    PATH=/app:/app/vendor/bin:/root/.composer/vendor/bin:$PATH \
    TERM=linux \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update -qq \
    && apt-get install -qy --no-install-recommends \
        git \
        openssl \
        librecode0 \
        uuid-dev \
        libmagickwand-dev \
        libsasl2-dev \
        imagemagick \
        libmagickwand-dev \
        libmagickcore-dev \
        libsqlite3-0 \
        libxml2


RUN apt-get update -qq \
    && apt-get install -qy --no-install-recommends \
        autoconf \
        file \
        g++ \
        gcc \
        libc-dev \
        make \
        cmake \
        curl \
        pkg-config \
        libtool \
        tar \
        libmcrypt-dev \
        libpng-dev \
        libonig-dev \
        libzip-dev \
        zip \
        unzip \
        wget

RUN mkdir /var/log/php

RUN docker-php-ext-install \
        gd \
        mysqli \
        pdo \
        pdo_mysql \
        tokenizer \
        opcache \
        exif \
        zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer


# Install phpunit
RUN wget https://phar.phpunit.de/phpunit-9.phar && \
        chmod +x phpunit-9.phar && \
        mv phpunit-9.phar /usr/local/bin/phpunit

#install ssh also create dir for linking ssh keys
RUN  apt-get -yq update && \
     apt-get -yqq install ssh
RUN mkdir ~/.ssh