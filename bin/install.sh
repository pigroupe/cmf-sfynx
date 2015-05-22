#!/bin/sh
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
fi
php -d memory_limit=1024M composer.phar install --no-interaction
php composer.phar dump-autoload --optimize