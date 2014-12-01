#!/bin/sh

read -p "Are you sure you want to reset all data from the project ? (y/n) " RESP
if [ "$RESP" = "y" ]; then
  echo "0- Clears all metadata cache for an entity manager"
  php app/console doctrine:cache:clear-metadata
  echo "1- Clears all query cache for an entity manager"
  php app/console doctrine:cache:clear-query
  echo "2- Clears result cache for an entity manager"
  php app/console doctrine:cache:clear-result
  echo "3- Drops the configured databases"
  php app/console doctrine:database:drop --force
  php app/console doctrine:database:create
  echo "4- Executes (or dumps) the SQL needed to generate the database schema"
  php app/console doctrine:schema:update --force
  echo "5- Load data fixtures to your database."
  php app/console doctrine:fixtures:load 
  echo "6- Extract a new user of the excel"
  echo "7-  Dumps all assets to the filesystem"
  php app/console assetic:dump
  echo "8-  Installs bundles web assets under a public web directory"
  php app/console assets:install
  echo "9- Reset project cache"
  php app/console cache:clear --env=prod --no-debug
  php app/console cache:clear --env=dev --no-debug
  sudo service apache2 reload
  sudo chmod -R 777 app/logs/
  sudo chmod -R 777 app/cache/
  echo "10 - we generate documentations"
  rm -rf doc/phpdocumentor/*
  rm -rf web/phpdocumentor/*
  rm -rf doc/uml/htmlnew/*
  rm -rf doc/uml/html/*
  rm -rf doc/uml/php/*
  rm -rf doc/uml/xmi/*

  mkdir -p doc/uml/xmi/auth-bundle
  mkdir -p doc/uml/php/auth-bundle
  mkdir -p doc/uml/html/auth-bundle
  mkdir -p doc/uml/htmlnew/auth-bundle
  mkdir -p doc/phpdocumentor/auth-bundle
  mkdir -p web/phpdocumentor/auth-bundle
  mkdir -p doc/phpmd/auth-bundle
  mkdir -p doc/phpcpd/auth-bundle
  phpuml vendor/sfynx-project/auth-bundle/Sfynx -n UMLsfynx_AUTH -o doc/uml/xmi
  phpuml vendor/sfynx-project/auth-bundle/Sfynx -f php -o doc/uml/php/auth-bundle
  phpuml vendor/sfynx-project/auth-bundle/Sfynx -f htmlnew -o doc/uml/htmlnew/auth-bundle
  phpuml vendor/sfynx-project/auth-bundle/Sfynx -f html -o doc/uml/html/auth-bundle
  phpdoc -d vendor/sfynx-project/auth-bundle/Sfynx -t doc/phpdocumentor --template responsive
  cp -r doc/phpdocumentor/*  web/phpdocumentor/
  bin/phpmd vendor/sfynx-project/auth-bundle/Sfynx html unusedcode,codesize,design,naming > doc/phpmd/auth-bundle/report.html
  bin/phpcpd vendor/sfynx-project/auth-bundle/Sfynx > doc/phpcpd/auth-bundle/report.txt
else
  echo "Canceled"
fi
