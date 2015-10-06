#!/bin/bash
DIR=$1
PLATEFORM_PROJET_NAME=$2
source $DIR/provisioners/shell/env.sh

echo "*** NGINX ***"

# MYSQL
$DIR/provisioners/shell/lemp/installer-mysql.sh $DIR

# PHP
$DIR/provisioners/shell/lemp/installer-php.sh $DIR

# PHPMYADMIN
$DIR/provisioners/shell/lemp/installer-phpmyadmin.sh $DIR

# NGINX
$DIR/provisioners/shell/lemp/installer-nginx.sh $DIR

echo "Restart mysql for the config to take effect"
sudo service mysql restart

echo "Restart php5 fpm for the config to take effect"
sudo service php5-fpm restart

echo "Restart Nginx for the config to take effect"
sudo service nginx restart
