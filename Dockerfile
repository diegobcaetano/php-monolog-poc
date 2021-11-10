FROM composer AS composer
FROM php:8-cli
WORKDIR /usr/src/myapp

RUN apt-get update
RUN apt-get install zip unzip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./composer.json /usr/src/myapp
RUN composer config disable-tls true \
    && composer install --no-plugins --ignore-platform-reqs --no-dev --optimize-autoloader --apcu-autoloader

COPY ./script.php /usr/src/myapp

CMD [ "php", "./script.php" ]