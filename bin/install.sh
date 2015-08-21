#!/bin/sh
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
fi
if [ -f composer.phar ]; then
    php -d memory_limit=1024M composer.phar install --no-interaction
    php composer.phar dump-autoload --optimize
fi

echo "**** we create parameters.yml ****"
if [ -f app/config/parameters.yml ]; then
    rm app/config/parameters.yml
fi
cp app/config/parameters.yml.dist app/config/parameters.yml
sed -i 's/%%/%/g' app/config/parameters.yml

echo "**** we create phpunit.xml ****"
if [ ! -f app/phpunit.xml ]; then
    cp app/phpunit.xml.dist app/phpunit.xml
fi

echo "**** we run the phing script to initialize the project ****"
vendor/bin/phing -f config/phing/initialize.xml rebuild

#php app/console doctrine:database:drop --force --env=dev
#php app/console doctrine:database:create --env=dev
#php app/console doctrine:schema:create --env=dev
#php app/console doctrine:schema:update --force --env=dev
#php app/console doctrine:fixtures:load --env=dev
#php app/console lexik:monolog-browser:schema-create --env=dev
#php app/console cache:clear --env=dev