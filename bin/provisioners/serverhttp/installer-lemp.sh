#!/bin/bash

DIR=$(pwd)

echo "*** NGINX ***"

# MYSQL
$DIR/installer-mysql.sh $DIR

# PHP
$DIR/installer-php.sh $DIR

# PHPMYADMIN
$DIR/installer-phpmyadmin.sh $DIR

# NGINX
$DIR/installer-nginx.sh $DIR

echo "Restart mysql for the config to take effect"
sudo service mysql restart

echo "Restart php5 fpm for the config to take effect"
sudo service php5-fpm restart

echo "Restart Nginx for the config to take effect"
sudo service nginx restart
