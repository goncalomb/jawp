FROM composer:1.10 AS builder

COPY composer.json .
RUN composer install

COPY . .

FROM php:7.4-apache

COPY --from=builder /app/vendor /var/www/vendor
COPY --from=builder /app/www /var/www/html

EXPOSE 80
