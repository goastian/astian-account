FROM alpine:3.20
LABEL maintainer="Elvis Yerel Roman C. <yerel9212@yahoo.es>"
RUN apk add --no-cache \
    php83 \
    php83-fpm \
    php83-opcache \
    php83-cli \
    php83-mbstring \
    php83-xml \
    php83-curl \
    php83-pecl-redis \
    php83-pecl-memcached \
    php83-pcntl \
    php83-posix \
    php83-pdo \
    php83-pdo_pgsql \
    php83-zip \
    php83-tokenizer \
    php83-json \
    php83-phar \
    php83-fileinfo \
    php83-dom \
    php83-session \
    php83-simplexml \
    php83-xmlwriter \
    php83-soap \
    php83-openssl \
    php83-bcmath \
    php83-gd \
    php83-intl \
    php83-sodium \
    nginx \
    curl \
    supervisor \
    icu-data-full

# Add user and group www-data
RUN getent passwd www-data || adduser -S -G www-data -s /usr/sbin/nologin www-data

# Symlink php
RUN ln -sf /usr/bin/php83 /usr/bin/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

COPY . /var/www/
COPY docker/www.conf /etc/php83/php-fpm.d/www.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/laravel-scripts.sh /usr/local/bin/laravel-scripts.sh 
# supervisor config to manage services
RUN mkdir -p /etc/supervisor.d \
    && cp docker/laravel.ini /etc/supervisor.d/laravel.ini \
    && chmod 755 /etc/nginx/http.d/default.conf \
    && chmod 755 /usr/local/bin/laravel-scripts.sh

# Install js dependencies and remove after build
RUN apk add --no-cache --virtual .build-deps npm unzip \
    && composer install --no-dev --optimize-autoloader \
    && composer clear-cache \
    && npm install \
    && npm run production \
    && rm -rf node_modules \
    && npm cache clean --force \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && rm -rf /var/www/docker