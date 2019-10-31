FROM php:latest
WORKDIR /usr/src/myapp
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install libbz2-dev
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo pdo_mysql bz2