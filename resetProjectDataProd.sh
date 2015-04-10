#!/bin/sh

read -p "Are you sure you want to reset all data from the project ? (y/n) " RESP
if [ "$RESP" = "y" ]; then
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
  php app/console cache:clear --env=prod --no-debug
  sudo service apache2 reload
  sudo chmod -R 777 app/logs/
  sudo chmod -R 777 app/cache/
else
  echo "Canceled"
fi
