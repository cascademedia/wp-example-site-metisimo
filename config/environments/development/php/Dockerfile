# @TODO Make a generic PHP image capable of running WordPress...
FROM php:7.4.3-apache

RUN apt update \
    && apt -y install vim less zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure mysqli \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) pdo_mysql

ENV APACHE_DOCUMENT_ROOT /var/www/html
ENV APACHE_HTTP_PORT 80
ENV APACHE_HTTPS_PORT 443

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's!Listen 80!Listen ${APACHE_HTTP_PORT}!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!Listen 443!Listen ${APACHE_HTTPS_PORT}!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!:80!:${APACHE_HTTP_PORT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!:443!:${APACHE_HTTPS_PORT}!g' /etc/apache2/sites-available/*.conf
