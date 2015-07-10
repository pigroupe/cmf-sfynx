#!/bin/sh
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
    php -d memory_limit=1024M composer.phar install --no-interaction
    php composer.phar dump-autoload --optimize
fi

echo "**** we run the phing script to initialize the project ****"
vendor/bin/phing -f config/phing/initialize.xml rebuild