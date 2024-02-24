# Use uma imagem base do PHP
FROM php:8.3-fpm

CMD ./vendor/bin/sail artisan key:generate --force

CMD ./vendor/bin/sail artisan migrate --force

CMD ./vendor/bin/sail artisan db:seed --force

