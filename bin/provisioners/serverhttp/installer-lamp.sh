#!/bin/bash

DIR=$(pwd)

echo "*** NGINX ***"

# MYSQL
$DIR/installer-mysql.sh $DIR

# PHP
$DIR/installer-php.sh $DIR

# PHPMYADMIN
$DIR/installer-phpmyadmin.sh $DIR

# APACHE2
$DIR/installer-apache2.sh $DIR

echo "Restart mysql for the config to take effect"
sudo service mysql restart

echo "Restart php5 fpm for the config to take effect"
sudo service php5-fpm restart

echo "Restart Nginx for the config to take effect"
sudo service apache2 restart
