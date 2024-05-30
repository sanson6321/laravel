FROM php:8.2-fpm-bullseye

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
	git \
	curl \
	zip \
	unzip \
  && docker-php-ext-install pdo_mysql \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY . /var/www/html

RUN composer install
