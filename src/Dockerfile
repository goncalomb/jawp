FROM composer:1.10 AS builder

COPY composer.* ./
RUN composer install

COPY . .

FROM php:7.4-apache

COPY --from=builder /app/vendor /var/www/vendor
COPY --from=builder /app/www /var/www/html

EXPOSE 80

# highjack entrypoint to set owner of data directory
# https://github.com/docker-library/php/blob/74175669f4162058e1fb0d2b0cf342e35f9c0804/7.4/buster/apache/Dockerfile
ENTRYPOINT [ "/bin/sh", "-c", "chown www-data: /var/www/data && exec docker-php-entrypoint \"$@\"", "--" ]
CMD ["apache2-foreground"]
