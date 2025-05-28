FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libicu-dev zip \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache \
    && a2enmod rewrite

# 💥 Ce fichier est essentiel
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Affichage dans les logs de build (debug)
RUN echo "=== VHOST CONFIGURATION ===" \
    && cat /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
