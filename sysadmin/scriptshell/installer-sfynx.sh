#!/bin/sh
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
    php -d memory_limit=1024M composer.phar install --no-interaction
    php composer.phar dump-autoload --optimize
fi

#
mkdir -p app/cache
mkdir -p app/logs
mkdir -p app/cachesfynx/loginfailure
mkdir -p web/uploads/media
mkdir -p web/yui

sudo chmod -R 0777 app/cachesfynx
sudo chmod -R 0777 app/cache
sudo chmod -R 0777 app/logs
sudo chmod -R 0777 web/uploads
sudo chmod -R 0777 web/yui

# we run the phing script to initialize the sfynx project
bin/phing -f app/config/phing/initialize.xml rebuild

sudo chmod -R 0777 app/cachesfynx
sudo chmod -R 0777 app/cache
sudo chmod -R 0777 app/logs
sudo chmod -R 0777 web/uploads
sudo chmod -R 0777 web/yui