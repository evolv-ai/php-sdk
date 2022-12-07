FROM php:7.4-apache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

WORKDIR /var/www/html

COPY . /var/www/html

RUN \
	cd Example && \
	composer install
