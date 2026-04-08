#!/bin/zsh
echo "install composer with dev packages"
composer install
php bin/console c:c
php bin/console d:m:m -n
php-fpm
