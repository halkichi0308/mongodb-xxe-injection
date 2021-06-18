FROM php:7-apache
RUN pecl install mongodb && echo 'extension=mongodb.so' >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini
