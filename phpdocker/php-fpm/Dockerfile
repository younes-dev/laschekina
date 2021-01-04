FROM php:7.2-fpm
WORKDIR "/var/www/vipcrossing"

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip
RUN docker-php-ext-install opcache

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN mkdir -p /.composer/ && chown -R :1000 /.composer/ && chmod 777 -R /.composer/