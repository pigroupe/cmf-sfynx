#!/bin/sh

echo "0- Clears all metadata cache for an entity manager"
php app/console doctrine:cache:clear-metadata
echo "1- Clears all query cache for an entity manager"
php app/console doctrine:cache:clear-query
echo "2- Clears result cache for an entity manager"
php app/console doctrine:cache:clear-result
echo "3- Executes (or dumps) the SQL needed to generate the database schema"
php app/console doctrine:schema:update --force
echo "4-  Dumps all assets to the filesystem"
php app/console assetic:dump
echo "5-  Installs bundles web assets under a public web directory"
php app/console assets:install
echo "6- Reset project cache"
php app/console cache:clear --env=dev --no-debug
php app/console cache:clear --env=test --no-debug
php app/console cache:clear --env=prod --no-debug

#echo "7 - we generate documentations"
#rm -rf documentation/phpdocumentor/*
#rm -rf web/phpdocumentor/*
#rm -rf documentation/uml/htmlnew/*
#rm -rf documentation/uml/html/*
#rm -rf documentation/uml/php/*
#rm -rf documentation/uml/xmi/*

#mkdir -p doc/uml/xmi/auth-bundle
#mkdir -p doc/uml/php/auth-bundle
#mkdir -p doc/uml/html/auth-bundle
#mkdir -p doc/uml/htmlnew/auth-bundle
#mkdir -p doc/phpdocumentor/auth-bundle
#mkdir -p web/phpdocumentor/auth-bundle
#mkdir -p doc/phpmd/auth-bundle
#mkdir -p doc/phpcpd/auth-bundle

#phpuml vendor/sfynx-project/auth-bundle/Sfynx -n UMLsfynx_AUTH -o doc/uml/xmi
#phpuml vendor/sfynx-project/auth-bundle/Sfynx -f php -o doc/uml/php/auth-bundle
#phpuml vendor/sfynx-project/auth-bundle/Sfynx -f htmlnew -o doc/uml/htmlnew/auth-bundle
#phpuml vendor/sfynx-project/auth-bundle/Sfynx -f html -o doc/uml/html/auth-bundle
#phpdoc -d vendor/sfynx-project/auth-bundle/Sfynx -t doc/phpdocumentor --template responsive
#cp -r documentation/phpdocumentor/*  web/phpdocumentor/
#vendor/bin/phpmd vendor/sfynx-project/auth-bundle/Sfynx html unusedcode,codesize,design,naming > documentation/phpmd/auth-bundle/report.html
#vendor/bin/phpcpd vendor/sfynx-project/auth-bundle/Sfynx > documentation/phpcpd/auth-bundle/report.txt
